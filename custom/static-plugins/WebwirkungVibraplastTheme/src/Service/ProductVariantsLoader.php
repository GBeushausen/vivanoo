<?php

declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Service;

use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductCollection;
use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductEntity;
use Shopware\Core\Content\Property\Aggregate\PropertyGroupOption\PropertyGroupOptionEntity;
use Shopware\Core\Content\Property\PropertyGroupEntity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\AndFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\SalesChannel\Entity\SalesChannelRepository;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Swag\CustomizedProducts\Template\TemplateEntity;
use Webwirkung\VibraplastTheme\Config\ConfigProvider;
use Webwirkung\VibraplastTheme\Installer\PluginLifecycle;
use Webwirkung\VibraplastTheme\Struct\CustomVariantConfiguratorStruct;
use Webwirkung\VibraplastTheme\Struct\VariantsBuyTableStruct;

class ProductVariantsLoader
{
    public function __construct(
        private readonly SalesChannelRepository $productRepository,
        private readonly EntityRepository       $propertyGroupRepository,
        private readonly ConfigProvider         $configProvider
    )
    {

    }

    public function loadVariantsForProduct(string $productId, SalesChannelContext $salesChannelContext): ?VariantsBuyTableStruct
    {
        $criteria = new Criteria([$productId]);
        $criteria->addAssociation('media.media');
        $product = $this->productRepository->search($criteria, $salesChannelContext)->first();
        if (!$product instanceof SalesChannelProductEntity) {
            return null;
        }

        $variants = $this->fetchProductVariants($productId, $salesChannelContext);
        $tableVariants = $variants->filter(
            static fn(SalesChannelProductEntity $product) => !$product->hasExtension('swagCustomizedProductsTemplate'));
        $this->sortTableVariants($product, $tableVariants);
        $customConfiguratorVariants = $variants->filter(
            static fn(SalesChannelProductEntity $product) => $product->hasExtension('swagCustomizedProductsTemplate'));

        $salesChannelId = $salesChannelContext->getSalesChannelId();
        return new VariantsBuyTableStruct(
            $product,
            $tableVariants,
            $this->fetchVariantsTablePropertyGroup($product, $salesChannelContext, $tableVariants),
            $this->getMaxPropertyOption($tableVariants, $this->configProvider->getLengthPropertyId($salesChannelId)),
            $this->getMaxPropertyOption($tableVariants, $this->configProvider->getHeightPropertyId($salesChannelId)),
            $this->getMaxPropertyOption($tableVariants, $this->configProvider->getWidthPropertyId($salesChannelId)),
            $customConfiguratorVariants->count() > 0
        );
    }

    public function loadCustomVariantConfigurator(string $productId, string $variantPropertyOption, SalesChannelContext $salesChannelContext): ?CustomVariantConfiguratorStruct
    {
        $criteria = new Criteria();
        $criteria->addFilter(new AndFilter([
            new EqualsFilter('properties.id', $variantPropertyOption),
            new EqualsFilter('product.parentId', $productId)
        ]));
        $criteria->addAssociation('media.media');
        $product = $this->productRepository->search($criteria, $salesChannelContext)->filter(
            static fn(SalesChannelProductEntity $product) => $product->hasExtension('swagCustomizedProductsTemplate') &&
                $product->getExtension('swagCustomizedProductsTemplate') instanceof TemplateEntity
        )->first();

        if (!$product instanceof SalesChannelProductEntity) {
            return null;
        }

        $variants = $this->fetchProductVariants($productId, $salesChannelContext);
        $tableVariants = $variants->filter(
            static fn(SalesChannelProductEntity $product) => !$product->hasExtension('swagCustomizedProductsTemplate'));

        // Get the main product for sorting configuration
        $criteria = new Criteria([$productId]);
        $mainProduct = $this->productRepository->search($criteria, $salesChannelContext)->first();
        if ($mainProduct instanceof SalesChannelProductEntity) {
            $this->sortTableVariants($mainProduct, $tableVariants);
        }

        $customizedTemplate = $product->getExtension('swagCustomizedProductsTemplate');

        return new CustomVariantConfiguratorStruct(
            $product,
            $customizedTemplate,
            $this->fetchVariantsTablePropertyGroup($product, $salesChannelContext, $tableVariants),
            $variantPropertyOption
        );
    }

    private function getMaxPropertyOption(SalesChannelProductCollection $variants, string $propertyGroupId): float
    {
        $max = 0;

        foreach ($variants as $variant) {
            $propertyOption = $this->getProperty($variant, $propertyGroupId);

            if ($propertyOption === null) {
                continue;
            }

            $value = $propertyOption->getName() ?? (($t = $propertyOption->getTranslated())['name'] ?? null);
            if (!is_numeric($value)) {
                continue;
            }

            $max = max($max, (float)$value);
        }
        return $max;
    }

    private function getProperty(
        ProductEntity $product,
        string $groupId,
    ): ?PropertyGroupOptionEntity
    {
        foreach ($product->getProperties() as $property) {
            if ($property->getGroupId() === $groupId) {
                return $property;
            }
        }

        foreach ($product->getOptions() as $option) {
            if ($option->getGroupId() === $groupId) {
                return $option;
            }
        }

        return null;
    }

    private function fetchProductVariants(string $productId, SalesChannelContext $salesChannelContext): SalesChannelProductCollection
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('parentId', $productId));
        $criteria->addAssociation('options.group');
        $criteria->addAssociation('properties.group');
        return $this->productRepository->search($criteria, $salesChannelContext)->getEntities();
    }


    private function fetchVariantsTablePropertyGroup(
        ProductEntity       $product,
        SalesChannelContext $salesChannelContext,
        EntityCollection    $tableVariants
    ): ?PropertyGroupEntity
    {
        $variantsTablePropertyGroupId = $product->getTranslated()['customFields'][PluginLifecycle::WW_VARIANT_TABLE_PROPERTY_GROUP] ?? null;
        $variantsTablePropertyGroup = null;
        if ($variantsTablePropertyGroupId) {
            //We want to only show selection for options that are available in the table variants
            $availableOptionIds = [];
            /* @var ProductEntity $variant */
            foreach ($tableVariants as $variant) {
                $option = $variant->getProperties()->filterByGroupId($variantsTablePropertyGroupId)->first();
                if ($option instanceof PropertyGroupOptionEntity && !in_array($option->getId(), $availableOptionIds)) {
                    $availableOptionIds[] = $option->getId();
                }
            }

            $criteria = new Criteria([$variantsTablePropertyGroupId]);
            $criteria->addAssociation('options');
            $variantsTablePropertyGroup = $this->propertyGroupRepository->search($criteria, $salesChannelContext->getContext())->first();
            if ($variantsTablePropertyGroup instanceof PropertyGroupEntity) {
                $filtered = $variantsTablePropertyGroup
                    ->getOptions()
                    ->filter(
                        static fn(PropertyGroupOptionEntity $option) => in_array($option->getId(), $availableOptionIds, true)
                    );

                // sort ASC numerically by name
                $filtered->sort(
                    static function (PropertyGroupOptionEntity $a, PropertyGroupOptionEntity $b): int {
                        return (int)($a->getTranslated()['name'] ?? $a->getName()) <=> (int)($b->getTranslated()['name'] ??  $b->getName());
                    }
                );

                $variantsTablePropertyGroup->setOptions($filtered);
            }
        }

        return $variantsTablePropertyGroup;
    }

    private function sortTableVariants(ProductEntity $product, SalesChannelProductCollection $tableVariants): void
    {
        $sortingPropertyGroupId = $product->getTranslated()['customFields'][PluginLifecycle::WW_VARIANT_TABLE_SORTING_PROPERTY] ?? null;
        
        if (!$sortingPropertyGroupId) {
            return;
        }

        $tableVariants->sort(function (SalesChannelProductEntity $a, SalesChannelProductEntity $b) use ($sortingPropertyGroupId): int {
            $propertyA = $this->getProperty($a, $sortingPropertyGroupId);
            $propertyB = $this->getProperty($b, $sortingPropertyGroupId);

            if ($propertyA === null && $propertyB === null) {
                return 0;
            }
            if ($propertyA === null) {
                return 1;
            }
            if ($propertyB === null) {
                return -1;
            }
            
            $valueA = $propertyA->getTranslated()['name'] ?? $propertyA->getName();
            $valueB = $propertyB->getTranslated()['name'] ?? $propertyB->getName();

            if (is_numeric($valueA) && is_numeric($valueB)) {
                return (float)$valueA <=> (float)$valueB;
            }

            return strcasecmp($valueA, $valueB);
        });
    }
}
