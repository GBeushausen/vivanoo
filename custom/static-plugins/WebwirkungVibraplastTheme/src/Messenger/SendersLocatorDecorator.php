<?php

declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Messenger;

use Shopware\Core\Content\Category\DataAbstractionLayer\CategoryIndexingMessage;
use Shopware\Core\Content\Product\DataAbstractionLayer\ProductIndexingMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Sender\SendersLocatorInterface;

/**
 * Decorator that prevents dispatch of indexing messages by returning an empty
 * list of senders for ProductIndexingMessage and CategoryIndexingMessage.
 * This ensures that indexing only happens via manual dal:refresh:index commands.
 */
class SendersLocatorDecorator implements SendersLocatorInterface
{
    public function __construct(private readonly SendersLocatorInterface $inner)
    {
    }

    public function getSenders(Envelope $envelope): iterable
    {
        $message = $envelope->getMessage();

        $isIndexingMessage = $message instanceof ProductIndexingMessage || $message instanceof CategoryIndexingMessage;

        if ($isIndexingMessage && !$message->isFullIndexing) {
            return [];
        }


        foreach ($this->inner->getSenders($envelope) as $sender => $alias) {
            if (\is_string($sender) && !\is_string($alias)) {
                $actualAlias = $sender;
                $actualSender = $alias;
            } else {
                $actualAlias = \is_string($alias) ? $alias : null;
                $actualSender = $sender;
            }

            if ($isIndexingMessage && $actualAlias === 'async') {
                continue;
            }

            if (\is_string($sender) && !\is_string($alias)) {
                yield $actualAlias => $actualSender;
            } else {
                yield $actualSender => $actualAlias;
            }
        }

        return $this->inner->getSenders($envelope);
    }
}
