<?php
declare(strict_types=1);

namespace Webwirkung\VibraplastAbacusIntegration\Pricing\Calculator;

use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\System\Unit\UnitEntity;

/**
 * Calculator for square meter pricing. Products where price stored in Abacus is per square meter.
 */
class SquareMeterCalculator implements PricingCalculatorInterface
{
    private ?UnitEntity $unit = null;

    public function calculateQuantityForProduct(ProductEntity $product): float
    {
        $area = $this->computeAreaFromProduct($product);
        return $area ?? 1.0;
    }

    public function convertToPiecePriceForProduct(float $perUnitPrice, ProductEntity $product): ?float
    {
        $area = $this->computeAreaFromProduct($product);
        if ($area === null || $area <= 0) {
            return null;
        }
        return $perUnitPrice * $area;
    }

    private function computeAreaFromProduct(ProductEntity $product): ?float
    {
        $length = (float)$product->getLength();
        $width = (float)$product->getWidth();
        if ($length <= 0 || $width <= 0) {
            return null;
        }
        $lengthInMeters = $length / 1000.0;
        $widthInMeters = $width / 1000.0;
        return $lengthInMeters * $widthInMeters;
    }

    public function setUnit(?UnitEntity $unit): void
    {
        $this->unit = $unit;
    }

    public function getUnit(): ?UnitEntity
    {
        return $this->unit;
    }

    public function getUnitHtml(): string
    {
        return $this->unit?->getShortCode() ?? '';
    }

    public function hidePiecePrice(): bool
    {
        return false;
    }

    public function getBulkPriceSnippetKey(): string
    {
        return 'product.detail.variant-configurator.table.priceListContentSquareMeter';
    }
}
