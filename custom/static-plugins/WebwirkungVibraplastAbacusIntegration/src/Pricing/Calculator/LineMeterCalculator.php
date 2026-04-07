<?php
declare(strict_types=1);

namespace Webwirkung\VibraplastAbacusIntegration\Pricing\Calculator;

use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\System\Unit\UnitEntity;

/**
 * Calculator for line meter pricing. Products where price stored in Abacus is per one meter.
 */
class LineMeterCalculator implements PricingCalculatorInterface
{
    private ?UnitEntity $unit = null;

    public function calculateQuantityForProduct(ProductEntity $product): float
    {
        $lengthInMeters = $this->getLengthInMeters($product);
        return $lengthInMeters ?? 1.0;
    }

    public function convertToPiecePriceForProduct(float $perUnitPrice, ProductEntity $product): ?float
    {
        $lengthInMeters = $this->getLengthInMeters($product);
        if ($lengthInMeters === null || $lengthInMeters <= 0) {
            return null;
        }
        return $perUnitPrice * $lengthInMeters;
    }

    private function getLengthInMeters(ProductEntity $product): ?float
    {
        $length = (float)$product->getLength();
        if ($length <= 0) {
            return null;
        }
        return $length / 1000.0;
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
        return 'product.detail.variant-configurator.table.priceListContentLineMeter';
    }
}
