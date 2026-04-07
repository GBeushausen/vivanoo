<?php

declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\DataResolver;

use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\DataResolver\Element\ElementDataCollection;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\EntityResolverContext;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\ResolverContext;
use Shopware\Core\Content\Product\Cms\AbstractProductDetailCmsElementResolver;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Framework\Struct\ArrayStruct;
use Webwirkung\VibraplastTheme\Service\ProductVariantsLoader;

class VariantsBuyTableCmsElementResolver extends AbstractProductDetailCmsElementResolver
{
    public function __construct(private readonly ProductVariantsLoader $productVariantsLoader)
    {

    }

    public function getType(): string
    {
        return 'variants-buy-table';
    }

    public function enrich(CmsSlotEntity $slot, ResolverContext $resolverContext, ElementDataCollection $result): void
    {
        if (!$resolverContext instanceof EntityResolverContext) {
            return;
        }

        $product = $resolverContext->getEntity();

        if (!$product instanceof ProductEntity) {
            return;
        }
        $variantTableData = $this->productVariantsLoader->loadVariantsForProduct($product->getParentId() ?? $product->getId(), $resolverContext->getSalesChannelContext());
        if(!is_null($variantTableData)) {
            $slot->setData($variantTableData);
        }
    }
}