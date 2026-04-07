<?php

declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Twig;

use Shopware\Core\Content\Product\Aggregate\ProductMedia\ProductMediaEntity;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Content\Product\SalesChannel\Listing\ProductListingResult;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Struct\ArrayStruct;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PimSortingExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('wwGetPimSortedProducts', [$this, 'getPimSortedProducts']),
        ];
    }

    public function getPimSortedProducts(ProductListingResult $result, Context $context): array
    {
        $pimSortedProductIds = $result->getExtension('pimSortedProductIds') ?? new ArrayStruct([]);
        $pimSortedProductIds = $pimSortedProductIds->getVars();

        $this->assignCoverImages($result->getElements());
        return $this->getSortedProducts($result->getElements(), $pimSortedProductIds);
    }

    private function assignCoverImages(array $products): void
    {
        foreach ($products as $product) {
            $coverImage = $this->getCoverImage($product);

            if ($coverImage === null) {
                continue;
            }

            $product->setCover($coverImage);
        }
    }

    private function getCoverImage(ProductEntity $product): ?ProductMediaEntity
    {
        if (
            $product->getCoverId() === null
            && $product->getMedia() === null
        ) {
            return null;
        }

        if (
            $product->getCoverId() === null
            && $product->getMedia() !== null
        ) {
            return $product->getMedia()->first();
        }

        if ($product->getMedia() === null) {
            return null;
        }

        foreach ($product->getMedia() as $media) {
            if ($media->getId() === $product->getCoverId()) {
                return $media;
            }
        }

        return null;
    }

    private function getSortedProducts(
        array $products,
        array $pimSortedProductIds,
    ): array
    {
        $sortedProducts = [];

        foreach ($pimSortedProductIds as $productId) {
            foreach ($products as $product) {
                if ($product->getId() === $productId) {
                    $sortedProducts[$productId] = $product;
                    break;
                }
            }
        }

        return $sortedProducts;
    }
}
