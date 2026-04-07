<?php

declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Struct;

use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductEntity;
use Shopware\Core\Content\Property\PropertyGroupEntity;
use Shopware\Core\Framework\Struct\Struct;
use Swag\CustomizedProducts\Template\TemplateEntity;

class CustomVariantConfiguratorStruct extends Struct
{
    public function __construct(
        private readonly SalesChannelProductEntity $product,
        private readonly ?TemplateEntity           $customizedTemplate,
        private readonly ?PropertyGroupEntity      $variantsTablePropertyGroup,
        private readonly string                    $variantPropertyOption
    )
    {
    }

    public function getProduct(): SalesChannelProductEntity
    {
        return $this->product;
    }

    public function getCustomizedTemplate(): ?TemplateEntity
    {
        return $this->customizedTemplate;
    }

    public function getVariantsTablePropertyGroup(): ?PropertyGroupEntity
    {
        return $this->variantsTablePropertyGroup;
    }

    public function getVariantPropertyOption(): string
    {
        return $this->variantPropertyOption;
    }
}