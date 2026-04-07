<?php

declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Twig;

use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Webwirkung\VibraplastTheme\Service\ProductVariantsLoader;
use Webwirkung\VibraplastTheme\Struct\VariantsBuyTableStruct;

class VariantsTableExtension extends AbstractExtension
{

    public function __construct(
        private readonly ProductVariantsLoader $productVariantsLoader,
    )
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('fetchVariantsTableData', [$this, 'fetchVariantsTableData']),
        ];
    }

    public function fetchVariantsTableData(string $productId, SalesChannelContext $salesChannelContext): ?VariantsBuyTableStruct
    {
        return $this->productVariantsLoader->loadVariantsForProduct($productId, $salesChannelContext);
    }
}