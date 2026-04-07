<?php
declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Twig;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Content\Media\MediaEntity;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MediaExtension extends AbstractExtension
{
    public function __construct(
        private readonly EntityRepository $mediaRepository,
    )
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('wwMediaById', [$this, 'getMediaById']),
        ];
    }

    public function getMediaById(?string $id, Context $context): ?MediaEntity
    {
        if (!$id || !Uuid::isValid($id)) {
            return null;
        }

        $criteria = new Criteria([$id]);
        return $this->mediaRepository->search($criteria, $context)->get($id);
    }
}
