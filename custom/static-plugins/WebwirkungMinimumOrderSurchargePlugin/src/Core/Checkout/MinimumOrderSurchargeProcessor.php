<?php
declare(strict_types=1);

namespace Webwirkung\MinimumOrderSurchargePlugin\Core\Checkout;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartBehavior;
use Shopware\Core\Checkout\Cart\CartProcessorInterface;
use Shopware\Core\Checkout\Cart\LineItem\CartDataCollection;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\LineItem\LineItemCollection;
use Shopware\Core\Checkout\Cart\Price\QuantityPriceCalculator;
use Shopware\Core\Checkout\Cart\Price\Struct\AbsolutePriceDefinition;
use Shopware\Core\Checkout\Cart\Price\Struct\CalculatedPrice;
use Shopware\Core\Checkout\Cart\Price\Struct\QuantityPriceDefinition;
use Shopware\Core\Checkout\Cart\Tax\Struct\CalculatedTaxCollection;
use Shopware\Core\Checkout\Cart\Tax\Struct\TaxRuleCollection;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Webwirkung\VibraplastTheme\Checkout\Cart\CustomProductLineItemFactory;

class MinimumOrderSurchargeProcessor implements CartProcessorInterface
{
    public const SURCHARGE_LINE_ITEM_TYPE = 'minimum-order-surcharge';
    public const SURCHARGE_EXTENSION_NAME = 'minimumOrderSurcharge';

    public function __construct(
        private QuantityPriceCalculator $quantityPriceCalculator,
    )
    {
    }

    public function process(
        CartDataCollection $data,
        Cart $original,
        Cart $toCalculate,
        SalesChannelContext $context,
        CartBehavior $behavior
    ): void
    {
        $supportedTypes = [
            LineItem::PRODUCT_LINE_ITEM_TYPE,
            CustomProductLineItemFactory::CUSTOM_PRODUCT_LINE_ITEM_TYPE,
        ];
        $products = $original->getLineItems()->filter(
            fn(LineItem $lineItem) => in_array($lineItem->getType(), $supportedTypes)
        );

        if ($products->count() === 0) {
            return;
        }

        $calculatedValue = $this->getSurchargeCalculatedValue($toCalculate, $context);
        $taxRules = $this->getTaxRules($products, $original, $toCalculate);

        if ((int) $calculatedValue === 0) {
            $toCalculate->addExtension(self::SURCHARGE_EXTENSION_NAME, $this->getLineItem(0, $taxRules)->getPrice());

            return;
        }

        $lineItem = $this->getLineItem($calculatedValue, $taxRules);
        $this->addSurcharge($calculatedValue, $lineItem, $toCalculate, $context);

        $toCalculate->addExtension(self::SURCHARGE_EXTENSION_NAME, $lineItem->getPrice());
    }

    private function addSurcharge(
        float $value,
        LineItem $lineItem,
        Cart $toCalculate,
        SalesChannelContext $context
    ): void
    {
        $definition = new QuantityPriceDefinition(
            $value,
            $lineItem->getPrice()->getTaxRules(),
            $lineItem->getPrice()->getQuantity()
        );
        $calculated = $this->quantityPriceCalculator->calculate($definition, $context);
        $lineItem->setPrice($calculated);
        $lineItem->setPriceDefinition($definition);
        $toCalculate->add($lineItem);
    }

    private function getSurchargeCalculatedValue(
        Cart $toCalculate,
        SalesChannelContext $context
    ): float
    {
        $minimalOrderValue = $this->getMinimumOrderValue($toCalculate, $context);
        $subtotal = $toCalculate->getPrice()->getPositionPrice();
        $value = $minimalOrderValue - $subtotal;

        return $value > 0 ? $value : 0;
    }

    private function getMinimumOrderValue(Cart $cart, SalesChannelContext $context)
    {
        return $cart->getExtension(MinimumOrderSurchargeCollector::buildMinimumSurchargeExtensionName($context))->get('value') ?? 0;
    }

    private function getLineItem(
        float $surcharge,
        TaxRuleCollection $taxRules,
    ): LineItem
    {
        $lineItem = new LineItem(self::SURCHARGE_LINE_ITEM_TYPE, self::SURCHARGE_LINE_ITEM_TYPE);
        $lineItem->setLabel('Minimum Order Surcharge');
        $lineItem->setGood(false);
        $lineItem->setStackable(false);
        $lineItem->setRemovable(true);
        $lineItem->setPriceDefinition(new AbsolutePriceDefinition($surcharge));
        $lineItem->setPrice(
            new CalculatedPrice(
                $surcharge,
                $surcharge,
                new CalculatedTaxCollection(),
                $taxRules,
            )
        );

        return $lineItem;
    }

    private function getTaxRules(
        LineItemCollection $products,
        Cart $original,
        Cart $toCalculate,
    ): TaxRuleCollection
    {
        $taxRule = $toCalculate->getPrice()?->getTaxRules()?->highestRate();

        if ($taxRule !== null) {
            return new TaxRuleCollection([$taxRule]);
        }

        $taxRule = $original->getPrice()?->getTaxRules()?->highestRate();

        if ($taxRule !== null) {
            return new TaxRuleCollection([$taxRule]);
        }

        foreach ($products as $product) {
            $taxRules = $product->getPriceDefinition()?->getTaxRules();

            if ($taxRules instanceof TaxRuleCollection) {
                return $taxRules;
            }
        }

        return new TaxRuleCollection();
    }
}
