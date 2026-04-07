<?php declare(strict_types=1);

namespace Webwirkung\VibraplastAbacusIntegration\Decorator;

use Shopware\Core\Checkout\Cart\Price\QuantityPriceCalculator;
use Shopware\Core\Checkout\Cart\Price\Struct\QuantityPriceDefinition;
use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Content\Product\ProductCollection;
use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductEntity;
use Shopware\Core\Framework\Struct\ArrayStruct;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Webwirkung\VibraplastAbacusIntegration\Pricing\Calculator\CalculatorFactoryInterface;
use WebwirkungAbacusIntegration\Api\Endpoints\Payload\FindProductsPricesPayload;
use WebwirkungAbacusIntegration\Api\Endpoints\Payload\ProductPricePosition;
use WebwirkungAbacusIntegration\Exception\AbacusApiException;
use WebwirkungAbacusIntegration\Plugin\ConfigInterface;
use WebwirkungAbacusIntegration\Product\Product;
use WebwirkungAbacusIntegration\Service\Abacus\Action\OverwriteCustomerPriceInterface;
use WebwirkungAbacusIntegration\Service\Abacus\Price;

class OverwriteCustomerPriceDecorator implements OverwriteCustomerPriceInterface
{
    public function __construct(
        private Price                      $price,
        private QuantityPriceCalculator    $quantityPriceCalculator,
        private CalculatorFactoryInterface $calculatorFactory,
    )
    {
    }

    /**
     * @param ProductCollection $products as key abacusProductId
     * @throws AbacusApiException
     */
    public function overwriteMany(
        ?CustomerEntity     $customer,
        ProductCollection   $products,
        SalesChannelContext $salesChannelContext,
        ConfigInterface     $config,
        bool                $displayGross,
    ): void
    {
        $salesChannelId = $salesChannelContext->getSalesChannelId();
        $currency = $salesChannelContext->getCurrency();
        $debtorNumber = (int)(($customer?->getCustomFields() ?? [])['abacus_debtor_number'] ?? 0);
        $payload = $this->getFindProductsPricesPayload(
            $debtorNumber,
            $currency->getIsoCode(),
            $products,
            $salesChannelContext
        );

        $pricePositionsNotLoggedIn = [];
        if (
            $config->isPricingStrikeEnabled()
            && 0 !== $debtorNumber
        ) {
            $payloadNotLoggedIn = $this->getFindProductsPricesPayload(
                0,
                $currency->getIsoCode(),
                $products,
                $salesChannelContext
            );

            $pricePositionsNotLoggedIn = $this->price->getProductPrices($salesChannelId, $payloadNotLoggedIn);
        }


        try {
            $pricePositions = $this->price->getProductPrices($salesChannelId, $payload);
        } catch (\Exception) {
            $this->useFallbackPricing($products, $salesChannelContext);
            return;
        }
        $productsByAbacusIdentifier = $this->getProductsByAbacusIdentifier($products);
        foreach ($pricePositions as $pricePosition) {
            if (!isset($pricePosition['ProductId'])) {
                continue;
            }

            $abacusProductId = $pricePosition['ProductId'];
            $abacusVariantId = $pricePosition['VariantId'] ?? 0;

            $product = $productsByAbacusIdentifier[$abacusProductId . "-" . $abacusVariantId] ?? null;

            if (!$product) {
                continue;
            }

            $match = current(array_filter($pricePositionsNotLoggedIn, fn($i) => $i['ProductId'] === $pricePosition['ProductId'])) ?: [];


            // Adjust prices using calculator if necessary (product-based)
            $calculator = $this->calculatorFactory->getForProduct($product, $salesChannelContext->getContext(), $salesChannelContext->getSalesChannelId());

            $abacusUnitPrice = $displayGross
                ? (float)($pricePosition['PerUnitValue']['PriceInclTax'] ?? 0)
                : (float)($pricePosition['PerUnitValue']['PriceExclTax'] ?? 0);

            foreach (['PriceInclTax', 'PriceExclTax'] as $key) {
                if (isset($pricePosition['PerUnitValue'][$key])) {
                    $adjusted = $calculator->convertToPiecePriceForProduct((float)$pricePosition['PerUnitValue'][$key], $product);
                    if ($adjusted !== null) {
                        $pricePosition['PerUnitValue'][$key] = $adjusted;
                    }
                }
            }

            $this->overwriteProductPrice(
                $product,
                $pricePosition,
                $salesChannelContext,
                $displayGross,
                $match,
                $abacusUnitPrice
            );
        }
    }

    private function useFallbackPricing(ProductCollection $products, SalesChannelContext $salesChannelContext): void
    {
        /** @var SalesChannelProductEntity $product */
        foreach ($products as $product) {
            $calculator = $this->calculatorFactory->getForProduct(
                $product,
                $salesChannelContext->getContext(),
                $salesChannelContext->getSalesChannelId()
            );

            $currentCalculatedPrice = $product->getCalculatedPrice();
            $shopwareUnitPrice = $currentCalculatedPrice->getUnitPrice();

            $adjustedPrice = $calculator->convertToPiecePriceForProduct($shopwareUnitPrice, $product);

            $definition = new QuantityPriceDefinition(
                $adjustedPrice ?? $shopwareUnitPrice,
                $currentCalculatedPrice->getTaxRules(),
                1
            );

            $newPrice = $this->quantityPriceCalculator->calculate($definition, $salesChannelContext);
            $newPrice->addExtension('abacus_base_unit_price', new ArrayStruct(['price' => $shopwareUnitPrice]));
            $product->assign(['calculatedPrice' => $newPrice]);
        }
    }

    private function getProductsByAbacusIdentifier(ProductCollection $products): array
    {
        $productsByAbacusIdentifier = [];

        foreach ($products as $product) {
            if (!isset($product->getCustomFields()[Product::ABACUS_PRODUCT_ID])) {
                continue;
            }

            $abacusProductId = $product->getCustomFields()[Product::ABACUS_PRODUCT_ID];
            $abacusVariantId = $product->getCustomFields()[Product::ABACUS_PRODUCT_VARIANT_ID] ?? 0;

            $productsByAbacusIdentifier[$abacusProductId . "-" . $abacusVariantId] = $product;

        }

        return $productsByAbacusIdentifier;
    }

    /**
     * @param ProductCollection $products as key abacusProductId
     */
    private function getFindProductsPricesPayload(
        int                 $customerNumber,
        string              $currencyIsoCode,
        ProductCollection   $products,
        SalesChannelContext $salesChannelContext,
    ): FindProductsPricesPayload
    {
        $payload = array_reduce(
            $products->getElements(),
            function (array $acc, SalesChannelProductEntity $product) use ($salesChannelContext) {
                $customFields = $product->getCustomFields() ?? [];
                $calculator = $this->calculatorFactory->getForProduct($product, $salesChannelContext->getContext(), $salesChannelContext->getSalesChannelId());
                $quantity = $calculator->calculateQuantityForProduct($product);

                $acc[] = new ProductPricePosition(
                    (int)($customFields['abacus_product_id'] ?? 0),
                    (int)($customFields['abacus_product_variant_id'] ?? 0),
                    $quantity,
                    $product->getProductNumber()
                );

                return $acc;
            },
            []
        );

        return new FindProductsPricesPayload(
            $customerNumber,
            $currencyIsoCode,
            $payload,
        );
    }

    private function overwriteProductPrice(
        SalesChannelProductEntity $product,
        array                     $position,
        SalesChannelContext       $context,
        bool                      $displayGross,
        ?array                    $positionForNotLoggedIn,
        float                     $abacusUnitPrice,
    ): void
    {
        $unit = $displayGross
            ? (float)$position['PerUnitValue']['PriceInclTax']
            : (float)$position['PerUnitValue']['PriceExclTax'];

        $definition = new QuantityPriceDefinition(
            $unit,
            $product->getCalculatedPrice()->getTaxRules(),
            1
        );

        if (!empty($positionForNotLoggedIn)) {
            $unitNotLoggedIn = $displayGross
                ? (float)$positionForNotLoggedIn['PerUnitValue']['PriceInclTax']
                : (float)$positionForNotLoggedIn['PerUnitValue']['PriceExclTax'];

            $definition->setListPrice($unitNotLoggedIn);
        }

        $newPrice = $this->quantityPriceCalculator->calculate($definition, $context);
        $newPrice->addExtension('abacus_base_unit_price', new ArrayStruct(['price' => $abacusUnitPrice]));
        $product->assign(['calculatedPrice' => $newPrice]);
    }

}