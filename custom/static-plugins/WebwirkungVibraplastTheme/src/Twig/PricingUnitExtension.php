<?php
declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Twig;

use Shopware\Core\Checkout\Cart\Price\CashRounding;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Webwirkung\VibraplastAbacusIntegration\Pricing\Calculator\CalculatorFactoryInterface;

class PricingUnitExtension extends AbstractExtension
{
    public function __construct(
        private readonly CalculatorFactoryInterface $calculatorFactory,
        private CashRounding                        $rounding,
    )
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('wwPerUnitPricing', [$this, 'perUnitPricing'], ['is_safe' => ['html']]),
            new TwigFunction('wwComputedQuantityUnit', [$this, 'computedQuantityUnit'], ['is_safe' => ['html']]),
            new TwigFunction('wwGetBulkPriceText', [$this, 'getBulkPriceSnippetKey']),
        ];
    }

    /**
     * Calculates the price per calculated unit (m² or m) for the given product amount.
     * Returns an array with keys: value (float) and unitHtml (string), or null if piece-based.
     */
    public function perUnitPricing(?float $amount, SalesChannelProductEntity $product, SalesChannelContext $context): ?array
    {
        $calculator = $this->calculatorFactory->getForProduct($product, $context->getContext(), $context->getSalesChannelId());
        $unitHtml = $calculator->getUnitHtml();

        if (is_null($amount)) {
            $amount = $product->getCalculatedPrice()->getUnitPrice();
            $quantity = $calculator->calculateQuantityForProduct($product);
            if ($quantity <= 0.0) {
                return null;
            }

            return [
                'value' => $this->rounding->cashRound($amount / $quantity, $context->getContext()->getRounding()),
                'unitHtml' => $unitHtml,
                'hidePiecePrice'=> $calculator->hidePiecePrice(),
            ];
        }

        $value = $this->rounding->cashRound($amount, $context->getContext()->getRounding());

        return [
            'value' => $value,
            'unitHtml' => $unitHtml,
            'hidePiecePrice'=> $calculator->hidePiecePrice(),
        ];
    }

    /**
     * Computes the calculated quantity (e.g., m² or m) and its unit for display next to the quantity input.
     * Returns an array with keys: quantity (float rounded down to 6 decimals) and unitHtml (string), or null if piece-based.
     */
    public function computedQuantityUnit(ProductEntity $product, SalesChannelContext $context): ?array
    {
        $calculator = $this->calculatorFactory->getForProduct($product, $context->getContext(), $context->getSalesChannelId());
        $quantity = $calculator->calculateQuantityForProduct($product);
        if ($quantity <= 0) {
            return null;
        }

        $unitHtml = $calculator->getUnitHtml();
        if ($unitHtml === null) {
            return null;
        }

        return [
            'quantity' => $this->floorToDecimals($quantity, 6),
            'unitHtml' => $unitHtml,
        ];
    }

    public function getBulkPriceSnippetKey(ProductEntity $product, SalesChannelContext $context): string
    {
        $calculator = $this->calculatorFactory->getForProduct($product, $context->getContext(), $context->getSalesChannelId());

        return $calculator->getBulkPriceSnippetKey();
    }

    private function floorToDecimals(float $value, int $decimals): float
    {
        $factor = 10 ** $decimals;
        return floor($value * $factor) / $factor;
    }
}
