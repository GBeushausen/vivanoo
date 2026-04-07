<?php

declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Struct;

use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductCollection;
use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductEntity;
use Shopware\Core\Content\Property\PropertyGroupEntity;
use Shopware\Core\Framework\Struct\Struct;

class VariantsBuyTableStruct extends Struct
{
    public function __construct(
        private readonly SalesChannelProductEntity     $product,
        private readonly SalesChannelProductCollection $variants,
        private readonly ?PropertyGroupEntity          $variantsTablePropertyGroup,
        private readonly float                         $maxLength,
        private readonly float                         $maxHeight,
        private readonly float                         $maxWidth,
        private readonly bool                          $hasCustomProductVariants,

    )
    {
    }

    public function getProduct(): SalesChannelProductEntity
    {
        return $this->product;
    }

    public function getVariants(): SalesChannelProductCollection
    {
        return $this->variants;
    }

    public function getVariantsTablePropertyGroup(): ?PropertyGroupEntity
    {
        return $this->variantsTablePropertyGroup;
    }

    public function getMaxLength(): float
    {
        return $this->maxLength;
    }

    public function getMaxHeight(): float
    {
        return $this->maxHeight;
    }

    public function getMaxWidth(): float
    {
        return $this->maxWidth;
    }

    public function isHasCustomProductVariants(): bool
    {
        return $this->hasCustomProductVariants;
    }
}