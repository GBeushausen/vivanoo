<?php

declare(strict_types=1);

namespace Webwirkung\ProductFinder\DataResolver;

use Shopware\Core\Content\Category\CategoryDefinition;
use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\DataResolver\CriteriaCollection;
use Shopware\Core\Content\Cms\DataResolver\Element\AbstractCmsElementResolver;
use Shopware\Core\Content\Cms\DataResolver\Element\ElementDataCollection;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\ResolverContext;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\AndFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\ContainsFilter;
use Shopware\Core\Framework\Struct\ArrayStruct;
use Symfony\Component\HttpFoundation\RequestStack;
use Webwirkung\ProductFinder\Installer\PluginLifecycle;
use Webwirkung\TrailBooking\DAL\Trail\TrailDefinition;

class ProductFinderResolver extends AbstractCmsElementResolver
{
    public function __construct(protected readonly RequestStack $requestStack)
    {
    }

    public function getType(): string
    {
        return 'product-finder';
    }

    public function collect(CmsSlotEntity $slot, ResolverContext $resolverContext): ?CriteriaCollection
    {
        $criteria = new Criteria();
        $criteria->addFilter(new AndFilter([
            new ContainsFilter('customFields', PluginLifecycle::WW_PRODUCT_FINDER_STEP_1_PROPERTY_GROUPS),
            new ContainsFilter('customFields', PluginLifecycle::WW_PRODUCT_FINDER_STEP_2_PROPERTY_GROUPS),
            new ContainsFilter('customFields', PluginLifecycle::WW_PRODUCT_FINDER_STEP_3_PROPERTY_GROUPS),
        ]));
        $criteriaCollection = new CriteriaCollection();
        $criteriaCollection->add('cms_product_finder_categories_' . $slot->getUniqueIdentifier(), CategoryDefinition::class, $criteria);
        return $criteriaCollection;
    }

    public function enrich(CmsSlotEntity $slot, ResolverContext $resolverContext, ElementDataCollection $result): void
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!is_null($request)) {
            $categoryId = $request->query->get('category');
        }
        $categories = $result->get('cms_product_finder_categories_' . $slot->getUniqueIdentifier());

        $data = $slot->getData();

        if (is_null($data)) {
            $data = new ArrayStruct([]);
        }

        if ($data instanceof ArrayStruct) {
            $data->assign([
                'selectedCategoryId' => $categoryId ?? '',
                'categories' => array_values($categories->map(static fn($category) => ['id' => $category->getId(), 'name' => $category->getTranslated()['name'] ?? '']))
            ]);
            $slot->setData($data);
        }
    }
}