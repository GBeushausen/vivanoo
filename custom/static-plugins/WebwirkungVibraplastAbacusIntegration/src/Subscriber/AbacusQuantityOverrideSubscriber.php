<?php

declare(strict_types=1);

namespace Webwirkung\VibraplastAbacusIntegration\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Webwirkung\VibraplastAbacusIntegration\Pricing\Calculator\CalculatorFactoryInterface;
use WebwirkungAbacusIntegration\Event\AbacusOrderLineItemPricePrepareEvent;

class AbacusQuantityOverrideSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly CalculatorFactoryInterface $calculatorFactory,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AbacusOrderLineItemPricePrepareEvent::class => 'onPricePrepare',
        ];
    }

    public function onPricePrepare(AbacusOrderLineItemPricePrepareEvent $event): void
    {
        $lineItem = $event->getLineItem();
        $product = $lineItem->getProduct();
        if ($product === null) {
            return;
        }

        $order = $event->getOrder();
        $context = $event->getContext();
        $salesChannelId = $order->getSalesChannelId();

        $calculator = $this->calculatorFactory->getForProduct($product, $context, $salesChannelId);

        $perProductQty = max(0.0, $calculator->calculateQuantityForProduct($product));
        $orderedQty = $lineItem->getQuantity();
        $finalQty = $perProductQty * $orderedQty;

        if ($finalQty > 0) {
            $unitStored = round($event->getUnitStored() / $perProductQty, 2);
            $event->setQuantity($finalQty);
            $event->setUnitStored($unitStored);
        }


    }
}
