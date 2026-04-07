<?php
declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use WebwirkungAbacusIntegration\Event\PricingApiHtmlPriceReplaceEvent;

class PricingApiHtmlPriceReplaceSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            PricingApiHtmlPriceReplaceEvent::class => 'replaceVariantPrices',
        ];
    }

    public function replaceVariantPrices(PricingApiHtmlPriceReplaceEvent $event): void
    {
        $event->setNodesQuery("//div[contains(@class, 'product-detail-price-wrapper')]");
        $event->setProductPriceNodesQuery("//span[contains(@class, 'product-detail-price-pce')]");
    }
}
