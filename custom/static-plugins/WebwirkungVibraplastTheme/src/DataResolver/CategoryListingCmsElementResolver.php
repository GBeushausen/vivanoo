<?php declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\DataResolver;

use Shopware\Core\Content\Category\CategoryDefinition;
use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\DataResolver\Element\AbstractCmsElementResolver;
use Shopware\Core\Content\Cms\DataResolver\Element\ElementDataCollection;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\ResolverContext;
use Shopware\Core\Content\Cms\DataResolver\CriteriaCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Struct\ArrayStruct;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;


class CategoryListingCmsElementResolver extends AbstractCmsElementResolver
{
    public function getType(): string
    {
        return 'category-listing-element';
    }

    public function collect(CmsSlotEntity $slot, ResolverContext $resolverContext): ?CriteriaCollection
    {
        $selectedCategoryConfig = $slot->getFieldConfig()->get('categories')?? null;
        if ($selectedCategoryConfig === null || $selectedCategoryConfig->isMapped() || $selectedCategoryConfig->isDefault()) {
            return null;
        }
        $categories = $selectedCategoryConfig->getArrayValue();

        $criteria = new Criteria($categories);
        $criteria->addFilter(
            new EqualsAnyFilter('id', $categories)
        );
        $criteria->addFilter(
            new EqualsFilter('active', true)
        );

        $criteriaCollection = new CriteriaCollection();
        $criteriaCollection->add(
            'selected_categories',
            CategoryDefinition::class,
            $criteria
        );
        return $criteriaCollection;
    }

    public function enrich(CmsSlotEntity $slot, ResolverContext $resolverContext, ElementDataCollection $result): void
    {
        $result = $result->get('selected_categories') ?? null;
        if ($result !== null && $result->first() !== null) {
            $slot->setData($result);
        }
    }
}