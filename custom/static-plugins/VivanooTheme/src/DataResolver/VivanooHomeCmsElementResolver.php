<?php declare(strict_types=1);

namespace Vivanoo\VivanooTheme\DataResolver;

use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\DataResolver\CriteriaCollection;
use Shopware\Core\Content\Cms\DataResolver\Element\AbstractCmsElementResolver;
use Shopware\Core\Content\Cms\DataResolver\Element\ElementDataCollection;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\ResolverContext;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\Framework\Struct\ArrayStruct;
use Shopware\Core\System\SalesChannel\Entity\SalesChannelRepository;

class VivanooHomeCmsElementResolver extends AbstractCmsElementResolver
{
    private const LATEST_PRODUCTS_LIMIT = 4;

    public function __construct(
        private readonly SalesChannelRepository $productRepository,
    ) {
    }

    public function getType(): string
    {
        return 'vivanoo-home';
    }

    public function collect(CmsSlotEntity $slot, ResolverContext $resolverContext): ?CriteriaCollection
    {
        return null;
    }

    public function enrich(CmsSlotEntity $slot, ResolverContext $resolverContext, ElementDataCollection $result): void
    {
        $criteria = new Criteria();
        $criteria->setLimit(self::LATEST_PRODUCTS_LIMIT);
        $criteria->addSorting(new FieldSorting('createdAt', FieldSorting::DESCENDING));
        $criteria->addAssociation('cover.media');
        $criteria->addAssociation('categories');

        $products = $this->productRepository->search($criteria, $resolverContext->getSalesChannelContext())->getEntities();

        $slot->setData(new ArrayStruct([
            'products' => $products,
        ]));
    }
}
