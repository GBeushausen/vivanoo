<?php

declare(strict_types=1);

namespace Webwirkung\MinimumOrderSurchargePlugin\Subscriber;

use Shopware\Core\Checkout\Cart\Order\CartConvertedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Webwirkung\MinimumOrderSurchargePlugin\Core\Checkout\MinimumOrderSurchargeProcessor;

class CartSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [CartConvertedEvent::class => 'onCartConverted'];
    }

    public function onCartConverted(CartConvertedEvent $event): void
    {
        $data = $event->getConvertedCart();
        $lineItems = [];
        $removedLineItemIds = [];
        $extensionName = MinimumOrderSurchargeProcessor::SURCHARGE_EXTENSION_NAME;

        if ($event->getCart()->hasExtension($extensionName)) {
            $minimumOrderSurcharge = $event->getCart()->getExtension($extensionName);
            $data['customFields'][$extensionName] = $minimumOrderSurcharge;
        }

        foreach ($data['lineItems'] as $key => $lineItem) {
            if ($lineItem['type'] === MinimumOrderSurchargeProcessor::SURCHARGE_LINE_ITEM_TYPE) {
                $removedLineItemIds[] = $lineItem['id'];
                continue;
            }

            $lineItems[$key] = $lineItem;
        }

        $data['lineItems'] = $lineItems;
        if (!empty($removedLineItemIds) && isset($data['deliveries'])) {
            foreach ($data['deliveries'] as &$delivery) {
                if (isset($delivery['positions'])) {
                    $delivery['positions'] = array_filter($delivery['positions'], function ($position) use ($removedLineItemIds) {
                        return !in_array($position['orderLineItemId'], $removedLineItemIds);
                    });
                }
            }
        }

        $event->setConvertedCart($data);
    }
}
