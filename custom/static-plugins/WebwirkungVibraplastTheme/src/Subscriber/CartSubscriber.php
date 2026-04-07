<?php

declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Subscriber;

use Shopware\Core\Checkout\Cart\Order\CartConvertedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Webwirkung\VibraplastTheme\Checkout\Cart\CustomProductLineItemFactory;

class CartSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [CartConvertedEvent::class => 'onCartConverted'];
    }

    public function onCartConverted(CartConvertedEvent $event): void
    {
        $data = $event->getConvertedCart();

        foreach ($data['lineItems'] as $key => $lineItem) {
            if ($lineItem['type'] !== CustomProductLineItemFactory::CUSTOM_PRODUCT_LINE_ITEM_TYPE) {
                continue;
            }

            $lineItem['productId'] = $lineItem['referencedId'];
            $data['lineItems'][$key] = $lineItem;
        }

        $event->setConvertedCart($data);
    }
}