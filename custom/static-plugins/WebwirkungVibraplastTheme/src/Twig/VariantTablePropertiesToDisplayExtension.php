<?php

declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Twig;

use Shopware\Core\Content\Product\Aggregate\ProductTranslation\ProductTranslationCollection;
use Shopware\Core\Content\Product\Aggregate\ProductTranslation\ProductTranslationEntity;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductCollection;
use Shopware\Core\Content\Property\Aggregate\PropertyGroupOption\PropertyGroupOptionEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Webwirkung\VibraplastTheme\Config\ConfigProvider;
use Webwirkung\VibraplastTheme\Exception\NotFoundException;

class VariantTablePropertiesToDisplayExtension extends AbstractExtension
{
    /**
     * @var array<string, EntityCollection> ProductID => PropertyGroupCollection
     */
    private array $variantTablePropertiesToDisplay = [];

    public function __construct(
        private readonly ConfigProvider   $config,
        private readonly EntityRepository $productRepository,
        private readonly EntityRepository $propertyGroupRepository,
    )
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('wwGetVariantTablePropertiesToDisplay', [$this, 'getVariantTablePropertiesToDisplay']),
            new TwigFunction('wwVariantTableDimensionPropertiesToDisplay', [$this, 'getVariantTableDimensionPropertiesToDisplay']),
            new TwigFunction('wwVariantTableWidth', [$this, 'getVariantTableWidth']),
            new TwigFunction('wwVariantTableHeight', [$this, 'getVariantTableHeight']),
            new TwigFunction('wwVariantTableLength', [$this, 'getVariantTableLength']),
            new TwigFunction('wwVariantTableColor', [$this, 'getVariantTableColor']),
            new TwigFunction('wwShouldDisplayVariantTableProperty', [$this, 'shouldDisplayVariantTableProperty']),
            new TwigFunction('wwGetValuesForTablePropertiesToDisplay', [$this, 'getValuesForTablePropertiesToDisplay']),
            new TwigFunction('wwGetFiltersDataForTablePropertiesToDisplay', [$this, 'getFiltersDataForTablePropertiesToDisplay']),
        ];
    }

    public function getVariantTableHeight(
        ProductEntity $product,
    ): ?string
    {
        return $this->getVariantTableValue($product, $this->config->getHeightPropertyId());
    }

    public function getVariantTableWidth(
        ProductEntity $product,
    ): ?string
    {
        return $this->getVariantTableValue($product, $this->config->getWidthPropertyId());
    }

    public function getVariantTableLength(
        ProductEntity $product,
    ): ?string
    {
        return $this->getVariantTableValue($product, $this->config->getLengthPropertyId());
    }

    public function getVariantTableColor(
        ProductEntity $product,
    ): ?string
    {
        $property = $this->getProperty(
            $product,
            $this->config->getColorPropertyId(),
        );

        if ($property === null) {
            return null;
        }

        return $property->getColorHexCode();
    }

    public function getValuesForTablePropertiesToDisplay(
        ProductEntity $product,
    ): EntityCollection
    {
        $productId = $product->getParentId() ?? $product->getId();
        $variantTablePropertiesToDisplay = $this->variantTablePropertiesToDisplay[$productId] ?? [];
        $output = new EntityCollection();

        foreach ($variantTablePropertiesToDisplay as $variantTablePropertyToDisplay) {
            $groupId = $variantTablePropertyToDisplay->getId();

            if (
                $groupId === $this->config->getHeightPropertyId()
                || $groupId === $this->config->getWidthPropertyId()
                || $groupId === $this->config->getLengthPropertyId()
                || $groupId === $this->config->getColorPropertyId()
            ) {
                continue;
            }

            foreach ($product->getProperties() as $property) {
                if ($property->getGroupId() === $groupId) {
                    $output->set($groupId, $property);
                }
            }
        }

        return $output;
    }

    public function getFiltersDataForTablePropertiesToDisplay(
        SalesChannelProductCollection $products,
    ): EntityCollection
    {
        $groupedValues = [];

        foreach ($products as $product) {
            $values = $this->getValuesForTablePropertiesToDisplay($product);

            /** @var PropertyGroupOptionEntity $value */
            foreach ($values as $value) {
                if (!is_numeric($value->getName())) {
                    continue;
                }
                if (!isset($groupedValues[$value->getGroupId()])) {
                    $groupedValues[$value->getGroupId()] = $value;

                    continue;
                }

                /** @var PropertyGroupOptionEntity $currentValue */
                $currentValue = $groupedValues[$value->getGroupId()];

                if ((int)$value->getName() > (int)$currentValue->getName()) {
                    $groupedValues[$value->getGroupId()] = $value;
                }
            }
        }

        return new EntityCollection($groupedValues);
    }

    public function shouldDisplayVariantTableProperty(
        ProductEntity $product,
        string        $groupId,
    ): bool
    {
        $productId = $product->getParentId() ?? $product->getId();
        $variantTablePropertiesToDisplay = $this->variantTablePropertiesToDisplay[$productId];

        if (
            $groupId === $this->config->getHeightPropertyId()
            || $groupId === $this->config->getWidthPropertyId()
            || $groupId === $this->config->getLengthPropertyId()
            || $groupId === $this->config->getColorPropertyId()
        ) {
            return false;
        }

        return $variantTablePropertiesToDisplay->has($groupId);
    }

    public function getVariantTablePropertiesToDisplay(
        ProductEntity $product,
        Context       $context,
    ): EntityCollection
    {
        $parentId = $product->getParentId() ?? '';

        if ($parentId !== '' && isset($this->variantTablePropertiesToDisplay[$parentId])) {
            return $this->variantTablePropertiesToDisplay[$parentId];
        }

        try {
            if ($parentId !== '') {
                $product = $this->getProductById($parentId, $context, ['children.properties', 'translations']);
            }
        } catch (NotFoundException $e) {
            $product = $this->getProductById($product->getId(), $context, ['children.properties', 'translations']);
        }

        if (isset($this->variantTablePropertiesToDisplay[$product->getId()])) {
            return $this->variantTablePropertiesToDisplay[$product->getId()];
        }

        $customFields = $product->getCustomFields() ?? [];
        $variantTablePropertiesToDisplay =
            !empty($customFields['ww_variant_table_properties_to_display'])
                ? $customFields['ww_variant_table_properties_to_display']
                : (
            $this->getFallbackTranslatedCustomField($product->getTranslations(), 'ww_variant_table_properties_to_display')
                ?: []
            );
        $variantTablePropertiesToDisplay = $this->filterVariantTablePropertiesToDisplay($product, $variantTablePropertiesToDisplay);

        if (empty($variantTablePropertiesToDisplay)) {
            return new EntityCollection();
        }

        $output = $this->propertyGroupRepository->search(
            new Criteria($variantTablePropertiesToDisplay),
            $context,
        )->getEntities();

        $this->variantTablePropertiesToDisplay[$product->getId()] = $output;

        return $output;
    }

    private function getFallbackTranslatedCustomField(
        ProductTranslationCollection $collection,
        string                       $customFieldName
    ): mixed
    {
        /** @var ProductTranslationEntity $translation */
        foreach ($collection as $translation) {
            $fields = $translation->getCustomFields();

            if (empty($fields[$customFieldName])) {
                continue;
            }

            return $fields[$customFieldName];
        }

        return null;
    }

    public function getVariantTableDimensionPropertiesToDisplay(
        ProductEntity $product,
    ): EntityCollection
    {
        $productId = $product->getParentId() ?? $product->getId();
        $variantTablePropertiesToDisplay = $this->variantTablePropertiesToDisplay[$productId] ?? [];
        $output = new EntityCollection();

        foreach ($variantTablePropertiesToDisplay as $propertyGroup) {
            if ($propertyGroup->getId() === $this->config->getHeightPropertyId()) {
                $output->set('height', $propertyGroup);
            }

            if ($propertyGroup->getId() === $this->config->getWidthPropertyId()) {
                $output->set('width', $propertyGroup);
            }

            if ($propertyGroup->getId() === $this->config->getLengthPropertyId()) {
                $output->set('length', $propertyGroup);
            }

            if ($propertyGroup->getId() === $this->config->getColorPropertyId()) {
                $output->set('color', $propertyGroup);
            }
        }

        return $output;
    }

    /**
     * @throws NotFoundException
     */
    private function getProductById(
        string  $id,
        Context $context,
                $associations = []
    ): ProductEntity
    {
        $criteria = new Criteria([$id]);
        if (!empty($associations)) {
            $criteria->addAssociations($associations);
        }
        return $this->productRepository->search(
            $criteria,
            $context
        )->first() ?? throw new NotFoundException('Product not found');
    }

    private function getProperty(
        ProductEntity $product,
        string        $groupId,
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

    /**
     * Filter property group IDs based on child products' properties
     */
    private function filterVariantTablePropertiesToDisplay(
        ProductEntity $product,
        array         $variantTablePropertiesToDisplay
    ): array
    {
        $children = $product->getChildren();
        if ($children === null || $children->count() === 0) {
            return $variantTablePropertiesToDisplay;
        }

        $availableGroupIds = [];

        foreach ($children as $child) {
            $properties = $child->getProperties();
            if ($properties !== null) {
                foreach ($properties as $property) {
                    $availableGroupIds[] = $property->getGroupId();
                }
            }
        }

        $availableGroupIds = array_unique($availableGroupIds);
        return array_intersect($variantTablePropertiesToDisplay, $availableGroupIds);
    }

    public function getVariantTableValue(
        ProductEntity $product,
        string        $groupId,
    ): ?string
    {
        $property = $this->getProperty(
            $product,
            $groupId,
        );

        if ($property === null) {
            return null;
        }

        return $property->getTranslated()['name'] ?? null;
    }
}
