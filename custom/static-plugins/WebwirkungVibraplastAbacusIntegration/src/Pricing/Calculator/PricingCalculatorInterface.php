<?php
declare(strict_types=1);

namespace Webwirkung\VibraplastAbacusIntegration\Pricing\Calculator;

use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\System\Unit\UnitEntity;

interface PricingCalculatorInterface
{
    public function calculateQuantityForProduct(ProductEntity $product): float;
    public function convertToPiecePriceForProduct(float $perUnitPrice, ProductEntity $product): ?float;

    public function setUnit(?UnitEntity $unit): void;

    public function getUnit(): ?UnitEntity;

    public function getUnitHtml(): string;

    public function hidePiecePrice(): bool;

    public function getBulkPriceSnippetKey(): string;
}
