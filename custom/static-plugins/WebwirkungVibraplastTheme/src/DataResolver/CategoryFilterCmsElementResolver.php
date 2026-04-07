<?php declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\DataResolver;

use AllowDynamicProperties;
use Shopware\Core\Content\Category\CategoryDefinition;
use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\DataResolver\Element\AbstractCmsElementResolver;
use Shopware\Core\Content\Cms\DataResolver\Element\ElementDataCollection;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\ResolverContext;
use Shopware\Core\Content\Cms\DataResolver\CriteriaCollection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

#[AllowDynamicProperties] class CategoryFilterCmsElementResolver extends AbstractCmsElementResolver
{
    public function __construct(
        EntityRepository $categoryRepository
    )
    {
        $this->categoryRepository = $categoryRepository;
    }
    public function getType(): string
    {
        return 'category-filter-element';
    }

    public function collect(CmsSlotEntity $slot, ResolverContext $resolverContext): ?CriteriaCollection
    {
        $currentCategory = $resolverContext->getEntity()->getId();

        $criteria = new Criteria();
        $criteria->addFilter(
            new EqualsFilter('parentId', $currentCategory)
        );
        $criteria->addFilter(
            new EqualsFilter('active', true)
        );
        $criteria->addAssociation('children');
        $criteria->addAssociation('children.children');

        $criteriaCollection = new CriteriaCollection();
        $criteriaCollection->add('categories', CategoryDefinition::class, $criteria);

        return $criteriaCollection;
    }

    public function enrich(CmsSlotEntity $slot, ResolverContext $resolverContext, ElementDataCollection $result): void
    {
        $categories = $result->get('categories') ?? null;
        if ($categories !== null && $categories->first() !== null) {
            $slot->setData($categories);
        }
    }
}