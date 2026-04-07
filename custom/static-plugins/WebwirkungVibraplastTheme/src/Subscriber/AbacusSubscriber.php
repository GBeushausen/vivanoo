<?php

declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Webwirkung\VibraplastTheme\Checkout\Cart\CustomProductLineItemFactory;
use WebwirkungAbacusIntegration\Event\AbacusOrderLineItemAdditionalInfoEvent;

class AbacusSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [AbacusOrderLineItemAdditionalInfoEvent::class => 'onOrderLineItemAdditionalInfo'];
    }

    public function onOrderLineItemAdditionalInfo(AbacusOrderLineItemAdditionalInfoEvent $event): void
    {

        $lineItem = $event->getLineItem();
        if ($lineItem->getType() !== CustomProductLineItemFactory::CUSTOM_PRODUCT_LINE_ITEM_TYPE) {
            return;
        }

        $configuratorOptions = $lineItem->getPayload()['configuratorOptions'];
        if (empty($configuratorOptions)) {
            return;
        }
        foreach ($configuratorOptions as $option) {
            $event->pushAdditionalInfo($option['name'], $option['value']);
        }

    }
}