<?php

declare(strict_types=1);

namespace Webwirkung\GlossaryPlugin\Storefront\Controller\Provider;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

class GlossaryProvider
{
    public function __construct(
        private readonly EntityRepository $glossaryRepository
    )
    {
    }

    public function fetchGlossaryActive(Context $context): ?EntitySearchResult
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('active', true));

        return $this->glossaryRepository->search($criteria, $context);
    }

}