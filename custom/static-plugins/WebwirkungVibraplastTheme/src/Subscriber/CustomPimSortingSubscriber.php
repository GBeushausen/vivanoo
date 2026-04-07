<?php
declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Subscriber;

use Shopware\Core\Content\Category\CategoryEntity;
use Shopware\Core\Content\Product\Events\ProductListingCriteriaEvent;
use Shopware\Core\Content\Product\Events\ProductListingResultEvent;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\Struct\ArrayStruct;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CustomPimSortingSubscriber implements EventSubscriberInterface
{
    private array $defaultSortingOrder;

    public function __construct(
        private EntityRepository $categoryRepository,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProductListingResultEvent::class => [
                ['onProductListingResult', -9999],
            ],
            ProductListingCriteriaEvent::class => [
                ['onProductListingCriteria', -9999],
            ],
        ];
    }

    public function onProductListingCriteria(ProductListingCriteriaEvent $event): void
    {
        $request = $event->getRequest();
        $sorting = $request->request->get('order', '');

        if ($sorting !== 'pim-sorting') {
            return;
        }

        $criteria = $event->getCriteria();

        try {
            $defaultSortingOrder = $this->getDefaultSortingOrder($event);
        } catch (\Exception $e) {
            return;
        }

        $criteria->addFilter(new EqualsAnyFilter('id', $defaultSortingOrder));
    }

    public function onProductListingResult(ProductListingResultEvent $event): void
    {
        $result = $event->getResult();

        if ($result->getSorting() !== 'pim-sorting') {
            return;
        }

        try {
            $defaultSortingOrder = $this->getDefaultSortingOrder($event);
        } catch (\Exception $e) {
            return;
        }

        $result->setExtensions(
            [
                ...$result->getExtensions(),
                'pimSortedProductIds' => new ArrayStruct($defaultSortingOrder),
            ]
        );
    }

    /**
     * @throws \Exception
     */
    private function getDefaultSortingOrder(ProductListingResultEvent|ProductListingCriteriaEvent $event): array
    {
        if (isset($this->defaultSortingOrder)) {
            return $this->defaultSortingOrder;
        }

        $category = $this->getCategory($event);
        $this->defaultSortingOrder = $category->getCustomFields()['ww_category_sorting_default_order'] ?? [];

        return $this->defaultSortingOrder;
    }

    /**
     * @throws \Exception
     */
    private function getCategory(ProductListingResultEvent|ProductListingCriteriaEvent $event): CategoryEntity
    {
        $request = $event->getRequest();
        $navigationId = $request->attributes->get('navigationId', '');

        if ($navigationId === '') {
            throw new \Exception('Navigation ID is missing');
        }

        $result = $this->categoryRepository->search(
            new Criteria([$navigationId]),
            $event->getContext()
        );

        return $result->first();
    }
}
