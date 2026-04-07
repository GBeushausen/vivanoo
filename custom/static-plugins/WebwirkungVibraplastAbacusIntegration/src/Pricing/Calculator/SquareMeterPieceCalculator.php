<?php
declare(strict_types=1);

namespace Webwirkung\VibraplastAbacusIntegration\Pricing\Calculator;

use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\System\Unit\UnitEntity;

/**
 * Calculator for square meter piece pricing. Products where 1 square meter = 1 piece sold.
 */
class SquareMeterPieceCalculator implements PricingCalculatorInterface
{

    private ?UnitEntity $unit = null;

    public function calculateQuantityForProduct(ProductEntity $product): float
    {
        return 1.0;
    }

    public function convertToPiecePriceForProduct(float $perUnitPrice, ProductEntity $product): ?float
    {
        return null;
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
        return true;
    }

    public function getBulkPriceSnippetKey(): string
    {
        return 'product.detail.variant-configurator.table.priceListContentSquareMeterPiece';
    }
}
