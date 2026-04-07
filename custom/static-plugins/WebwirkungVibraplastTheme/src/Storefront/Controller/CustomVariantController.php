<?php

declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Storefront\Controller;

use Shopware\Core\Framework\Log\Package;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Webwirkung\VibraplastTheme\Service\ProductVariantsLoader;

#[Route(defaults: ['_routeScope' => ['storefront']])]
#[Package('storefront')]
class CustomVariantController extends StorefrontController
{
    public function __construct(
        private readonly ProductVariantsLoader $productVariantsLoader,
    )
    {
    }

    #[Route('/variant/custom-configurator/{productId}', name: 'frontend.variant.custom-configurator', defaults: ['XmlHttpRequest' => true], methods: ['GET'])]
    public function customConfigurator(
        string              $productId,
        Request             $request,
        SalesChannelContext $salesChannelContext): Response
    {
        $variantPropertyOption = $request->query->get('variantPropertyOption');

        return $this->renderStorefront('@WebwirkungVibraplastTheme/storefront/component/product/custom-variant-configurator.html.twig', [
            'data' => $this->productVariantsLoader->loadCustomVariantConfigurator($productId, $variantPropertyOption, $salesChannelContext)
        ]);
    }

}