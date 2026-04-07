<?php
declare(strict_types=1);

namespace Webwirkung\VibraplastAbacusIntegration\Pricing\Calculator;

use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\System\Unit\UnitEntity;

/**
 * Calculator for piece pricing. Products where price stored in Abacus is per one piece.
 */
class PieceCalculator implements PricingCalculatorInterface
{
    public function calculateQuantityForProduct(ProductEntity $product): float
    {
        return 1.0;
    }

    public function convertToPiecePriceForProduct(float $perUnitPrice, ProductEntity $product): ?float
    {
        // For piece-based products, Abacus already returns per-piece price; no conversion needed.
        return null;
    }

    public function setUnit(?UnitEntity $unit): void
    {
        // No unit needed for piece calculation
    }

    public function getUnit(): ?UnitEntity{
        return null;
    }

    public function getUnitHtml(): string
    {
        return '';
    }

    public function hidePiecePrice(): bool
    {
        return false;
    }

    public function getBulkPriceSnippetKey(): string
    {
        return 'product.detail.variant-configurator.table.priceListContent';
    }
}
