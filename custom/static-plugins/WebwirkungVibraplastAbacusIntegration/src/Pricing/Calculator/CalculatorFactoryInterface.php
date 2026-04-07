<?php
declare(strict_types=1);

namespace Webwirkung\VibraplastAbacusIntegration\Pricing\Calculator;

use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Framework\Context;

interface CalculatorFactoryInterface
{
    public function getForProduct(ProductEntity $product, Context $context, string $salesChannelId): PricingCalculatorInterface;
}
