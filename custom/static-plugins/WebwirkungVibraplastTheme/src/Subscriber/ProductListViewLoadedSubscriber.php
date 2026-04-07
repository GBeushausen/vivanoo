<?php declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Subscriber;

use Shopware\Core\Content\Product\Events\ProductListingCriteriaEvent;
use Shopware\Core\Content\Product\ProductEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductListViewLoadedSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ProductEvents::PRODUCT_LISTING_CRITERIA => 'onProductsLoaded'
        ];
    }

    public function onProductsLoaded(ProductListingCriteriaEvent $event): void
    {
        $criteria = $event->getCriteria();
        $criteria->addAssociation('swagCustomizedProductsTemplate');
        $criteria->addAssociation('children.swagCustomizedProductsTemplate');

    }
}