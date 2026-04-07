<?php

declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Cart;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartBehavior;
use Shopware\Core\Checkout\Cart\CartDataCollectorInterface;
use Shopware\Core\Checkout\Cart\CartException;
use Shopware\Core\Checkout\Cart\CartProcessorInterface;
use Shopware\Core\Checkout\Cart\Delivery\Struct\DeliveryInformation;
use Shopware\Core\Checkout\Cart\Delivery\Struct\DeliveryTime;
use Shopware\Core\Checkout\Cart\LineItem\CartDataCollection;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\LineItem\QuantityInformation;
use Shopware\Core\Checkout\Cart\Price\QuantityPriceCalculator;
use Shopware\Core\Checkout\Cart\Price\Struct\CalculatedPrice;
use Shopware\Core\Checkout\Cart\Price\Struct\QuantityPriceDefinition;
use Shopware\Core\Checkout\Cart\Price\Struct\ReferencePriceDefinition;
use Shopware\Core\Content\Product\Cart\ProductGateway;
use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductEntity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\Unit\UnitEntity;
use Webwirkung\VibraplastTheme\Checkout\Cart\CustomProductLineItemFactory;

class CustomVariantProcessor implements CartProcessorInterface, CartDataCollectorInterface
{
    public function __construct(
        private readonly ProductGateway          $productGateway,
        private readonly QuantityPriceCalculator $calculator,
        private readonly EntityRepository        $propertyGroupOptionRepository
    )
    {
    }

    public function collect(CartDataCollection $data, Cart $original, SalesChannelContext $context, CartBehavior $behavior): void
    {
        $customVariantLineItems = $original->getLineItems()
            ->filterType(CustomProductLineItemFactory::CUSTOM_PRODUCT_LINE_ITEM_TYPE);

        if ($customVariantLineItems->count() === 0) {
            return;
        }


        $productIds = $customVariantLineItems->getReferenceIds();
        $optionsIds = [];

        $products = $this->productGateway->get($productIds, $context);
        foreach ($customVariantLineItems as $customVariantLineItem) {
            if ($customVariantLineItem->hasPayloadValue('variantProperty')) {
                $optionsIds[] = $customVariantLineItem->getPayloadValue('variantProperty')['optionId'];
            }

            $product = $products->get($customVariantLineItem->getReferencedId());
            if (!$product instanceof SalesChannelProductEntity) {
                continue;
            }

            $data->set('product-' . $customVariantLineItem->getReferencedId(), $product);

            $priceDefinition = $this->buildPriceDefinition($product->getCalculatedPrices()->first() ?? $product->getCalculatedPrice(), $customVariantLineItem->getQuantity());

            $customVariantLineItem->setPriceDefinition($priceDefinition);

            $quantityInformation = new QuantityInformation();

            $quantityInformation->setMinPurchase(
                $product->getMinPurchase() ?? 1
            );

            $quantityInformation->setMaxPurchase(
                $product->getCalculatedMaxPurchase()
            );

            $quantityInformation->setPurchaseSteps(
                $product->getPurchaseSteps() ?? 1
            );

            $customVariantLineItem->setQuantityInformation($quantityInformation);
            $this->setDeliveryInformation($customVariantLineItem, $product);
            $this->setProductNumber($customVariantLineItem, $product);
            $this->setStates($customVariantLineItem, $product);
            $this->setOptions($customVariantLineItem);

            $customVariantLineItem->addExtension('unit', $product->getUnit());
        }

        if (!empty($optionsIds)) {
            $criteria = new Criteria(array_unique($optionsIds));
            $criteria->addAssociation('group');
            $options = $this->propertyGroupOptionRepository->search($criteria, $context->getContext());
            $data->set('customVariantOptions', $options);
        }
    }

    public function process(CartDataCollection $data, Cart $original, Cart $toCalculate, SalesChannelContext $context, CartBehavior $behavior): void
    {
        $customVariantLineItems = $original->getLineItems()
            ->filterType(CustomProductLineItemFactory::CUSTOM_PRODUCT_LINE_ITEM_TYPE);

        if ($customVariantLineItems->count() === 0) {
            return;
        }

        $customVariantOptions = $data->get('customVariantOptions');

        foreach ($customVariantLineItems as $customVariantLineItem) {
            $definition = $customVariantLineItem->getPriceDefinition();

            if (!$definition instanceof QuantityPriceDefinition) {
                throw CartException::missingLineItemPrice($customVariantLineItem->getId());
            }
            $definition->setQuantity($customVariantLineItem->getQuantity());
            $referencePrice = $definition->getReferencePriceDefinition();
            $basePrice = $this->calculator->calculate($definition, $context);
            $dimensions = $customVariantLineItem->getPayloadValue('configuratorOptions') ?? [];

            $purchaseUnit = (!is_null($referencePrice)) ? $referencePrice->getPurchaseUnit() : 1;
            if (!empty($dimensions)) {
                $cubicMilimeter = 1;
                foreach ($dimensions as $dimension) {
                    $cubicMilimeter *= (int)$dimension['value'];
                }
                /** @var UnitEntity $unit */
                $unit = $customVariantLineItem->hasExtension('unit') ? $customVariantLineItem->getExtension('unit') : null;
                $cubicMeter = $this->calculate($dimensions, $cubicMilimeter, $unit?->getTranslated()['shortCode'] ?? 'm³');

                $customUnitPrice = round($basePrice->getUnitPrice() * $cubicMeter / $purchaseUnit, 2);

                $newDefinition = new QuantityPriceDefinition(
                    $customUnitPrice,
                    $basePrice->getTaxRules(),
                    $customVariantLineItem->getQuantity()
                );
                $newDefinition->setReferencePriceDefinition($referencePrice);

                $basePrice = $this->calculator->calculate($newDefinition, $context);
            }

            $customVariantLineItem->setPrice($basePrice);

            if ($customVariantOptions instanceof EntitySearchResult && $customVariantLineItem->hasPayloadValue('variantProperty')) {
                $optionId = $customVariantLineItem->getPayloadValue('variantProperty')['optionId'];
                $option = $customVariantOptions->get($optionId);
                if ($option) {
                    $customVariantLineItem->setPayloadValue('variantPropertyOptionData', $option->jsonSerialize());
                }
            }

            $toCalculate->add($customVariantLineItem);
        }


    }

    private function buildPriceDefinition(CalculatedPrice $price, int $quantity): QuantityPriceDefinition
    {
        $definition = new QuantityPriceDefinition($price->getUnitPrice(), $price->getTaxRules(), $quantity);
        if ($price->getListPrice() !== null) {
            $definition->setListPrice($price->getListPrice()->getPrice());
        }

        if ($price->getReferencePrice() !== null) {
            $definition->setReferencePriceDefinition(
                new ReferencePriceDefinition(
                    $price->getReferencePrice()->getPurchaseUnit(),
                    $price->getReferencePrice()->getReferenceUnit(),
                    $price->getReferencePrice()->getUnitName()
                )
            );
        }

        return $definition;
    }

    private function calculate(array $dimensions, float $value, string $unit): float
    {
        if (count($dimensions) === 2) {
            return match ($unit) {
                'mm²' => $value,
                'cm²' => $value / 100,
                'dm²' => $value / 10000,
                'm²' => $value / 1000000,
                default => $value / 1000000,
            };
        }
        if (count($dimensions) === 3) {
            return match ($unit) {
                'mm³' => $value,
                'cm³' => $value / 1000,
                'dm³' => $value / 1000000,
                'm³' => $value / 1000000000,
                default => $value / 1000000000,
            };
        }

        return $value;
    }

    private function setDeliveryInformation(LineItem $lineItem, SalesChannelProductEntity $product): void
    {
        if ($product->getDeliveryTime() === null) {
            return;
        }

        $deliveryInformation = new DeliveryInformation(
            0,
            null,
            false,
            null,
            DeliveryTime::createFromEntity($product->getDeliveryTime())
        );
        $lineItem->setDeliveryInformation($deliveryInformation);
    }

    private function setProductNumber(LineItem $lineItem, SalesChannelProductEntity $product): void
    {
        if ($lineItem->hasPayloadValue('productNumber')) {
            return;
        }

        $lineItem->setPayloadValue('productNumber', $product->getProductNumber());
    }

    private function setOptions(LineItem $lineItem): void
    {
        if ($lineItem->hasPayloadValue('options')) {
            return;
        }

        $options = array_map(
            fn($option) => [
                'group' => str_replace(' (mm)', '', $option['name']),
                'option' => $option['value'],
            ],
            $lineItem->getPayloadValue('configuratorOptions')
        );
        $lineItem->setPayloadValue('options', $options);
    }

    private function setStates(LineItem $lineItem, SalesChannelProductEntity $product): void
    {
        if ($lineItem->getStates() !== []) {
            return;
        }

        $lineItem->setStates($product->getStates());
    }
}
