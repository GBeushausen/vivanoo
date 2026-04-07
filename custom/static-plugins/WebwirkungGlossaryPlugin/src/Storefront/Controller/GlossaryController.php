<?php declare(strict_types=1);

namespace Webwirkung\GlossaryPlugin\Storefront\Controller;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Log\Package;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Webwirkung\GlossaryPlugin\Storefront\Controller\Provider\GlossaryProvider;


#[Route(defaults: ['_routeScope' => ['storefront']])]
#[Package('storefront')]
class GlossaryController extends StorefrontController
{
    public function __construct(
        private readonly GlossaryProvider $glossaryProvider,
    )
    {
    }

    #[Route('/glossary', name: 'frontend.glossary', methods: ['GET', 'POST'], defaults: ['XmlHttpRequest' => true])]
    public function glossaryList(Context $context): JsonResponse
    {
        return new JsonResponse($this->glossaryProvider->fetchGlossaryActive($context));
    }
}