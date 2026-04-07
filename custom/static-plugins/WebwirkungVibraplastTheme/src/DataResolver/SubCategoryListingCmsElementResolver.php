<?php

declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\DataResolver;

use Shopware\Core\Content\Category\CategoryCollection;
use Shopware\Core\Content\Category\CategoryDefinition;
use Shopware\Core\Content\Category\CategoryEntity;
use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\DataResolver\CriteriaCollection;
use Shopware\Core\Content\Cms\DataResolver\Element\AbstractCmsElementResolver;
use Shopware\Core\Content\Cms\DataResolver\Element\ElementDataCollection;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\ResolverContext;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Struct\ArrayStruct;

class SubCategoryListingCmsElementResolver extends AbstractCmsElementResolver
{
    public function getType(): string
    {
        return 'sub-category-listing-element';
    }

    public function collect(CmsSlotEntity $slot, ResolverContext $resolverContext): ?CriteriaCollection
    {
        $fieldConfig = $slot->getFieldConfig();

        $selectedParentCategoryConfig = $fieldConfig->get('category');
        if (!$selectedParentCategoryConfig->getValue()) {
            if (!$resolverContext->getRequest()->get('navigationId')) {
                return null;
            }
            $parentCategoryId = $resolverContext->getRequest()->get('navigationId');
        } else {
            $parentCategoryId = $selectedParentCategoryConfig->getValue();
        }

        // Criteria to fetch child categories with products and properties
        $criteria = new Criteria();
        $criteria->addFilter(
            new EqualsFilter('parentId', $parentCategoryId),
        );
        $criteria->addFilter(
            new EqualsFilter('active', true),
        );
        $criteria->addAssociation('children.children');

        $criteriaCollection = new CriteriaCollection();
        $criteriaCollection->add(
            'child_categories',
            CategoryDefinition::class,
            $criteria,
        );

        return $criteriaCollection;
    }

    public function enrich(CmsSlotEntity $slot, ResolverContext $resolverContext, ElementDataCollection $result): void
    {
        $categories = $result->get('child_categories') ?? null;

        if (!$categories) {
            return;
        }

        $collection = $categories->getEntities();

        if (!$collection instanceof CategoryCollection) {
            return;
        }

        $sorted = $collection->sortByPosition();
        /** @var CategoryEntity $category */
        foreach ($sorted as $category) {
            $categoryData[$category->getId()] = [
                'category' => $category,
            ];
        }

        $slot->setData(new ArrayStruct($categoryData, 'child_categories'));

    }
}
