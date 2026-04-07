<?php

declare(strict_types=1);

namespace Webwirkung\ProductFinder\Storefront\Controller;

use Shopware\Core\Content\Category\CategoryEntity;
use Shopware\Core\Content\Media\MediaEntity;
use Shopware\Core\Content\Product\SalesChannel\Listing\AbstractProductListingRoute;
use Shopware\Core\Content\Property\Aggregate\PropertyGroupOption\PropertyGroupOptionEntity;
use Shopware\Core\Content\Property\PropertyGroupEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\Exception\StorefrontException;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Webwirkung\ProductFinder\Installer\PluginLifecycle;

#[Route(defaults: ['_routeScope' => ['storefront']])]
#[Package('storefront')]
class ProductFinderController extends StorefrontController
{
    public function __construct(
        private readonly EntityRepository            $categoryRepository,
        private readonly EntityRepository            $propertyGroupRepository,
        private readonly EntityRepository            $propertyGroupOptionRepository,
        private readonly EntityRepository            $mediaRepository,
        private readonly AbstractProductListingRoute $listingRoute
    )
    {

    }

    #[Route('/product-finder/steps/{categoryId}/{stepNumber}', name: 'frontend.product-finder.step', defaults: ['XmlHttpRequest' => true], methods: ['GET'])]
    public function productFinderStep(string $categoryId, int $stepNumber, SalesChannelContext $salesChannelContext): JsonResponse
    {
        if (!Uuid::isValid($categoryId)) {
            throw new StorefrontException(Response::HTTP_BAD_REQUEST, 'PRODUCT_FINDER_CATEGORY_ID_NOT_VALID', 'Category id is not valid');
        }

        $category = $this->fetchCategory($categoryId, $salesChannelContext->getContext());
        if (is_null($category)) {
            throw new StorefrontException(Response::HTTP_BAD_REQUEST, 'PRODUCT_FINDER_CATEGORY_NOT_FOUND', 'Category not found');
        }
        $customFieldName = sprintf('ww_product_finder_step_%s_property_groups', $stepNumber);
        if (is_null($category->getCustomFields()) || !array_key_exists($customFieldName, $category->getCustomFields())) {
            throw new StorefrontException(Response::HTTP_BAD_REQUEST, 'PRODUCT_FINDER_STEP_NOT_FOUND', 'Step properties not found');
        }
        $stepProperties = $this->fetchPropertyGroups($category->getCustomFields()[$customFieldName], $salesChannelContext->getContext());

        return new JsonResponse([
            'stepLabel' => $category->getCustomFields()[sprintf('ww_product_finder_step_%s_question', $stepNumber)] ?? '',
            'step1Question2' => $category->getCustomFields()['ww_product_finder_step_1_question_2'] ?? '',
            'stepsLabels' => [
                $category->getCustomFields()[PluginLifecycle::WW_PRODUCT_FINDER_STEP_1_LABEL] ?? '',
                $category->getCustomFields()[PluginLifecycle::WW_PRODUCT_FINDER_STEP_2_LABEL] ?? '',
                $category->getCustomFields()[PluginLifecycle::WW_PRODUCT_FINDER_STEP_3_LABEL] ?? '',
                $this->trans('WebwirkungProductFinder.productFinder.resultLabel'),
            ],
            'stepProperties' => $stepProperties,
            'stepSelectionType' => $stepNumber < 3 ? 'single' : 'multi' //TODO in future we can make it dynamic from category custom field
        ]);
    }


    #[Route('/product-finder/result/{categoryId}', name: 'frontend.product-finder.result', defaults: ['XmlHttpRequest' => true], methods: ['GET'])]
    public function productFinderResult(string $categoryId, Request $request, SalesChannelContext $salesChannelContext): Response
    {
        if (!Uuid::isValid($categoryId)) {
            throw new StorefrontException(Response::HTTP_BAD_REQUEST, 'PRODUCT_FINDER_CATEGORY_ID_NOT_VALID', 'Category id is not valid');
        }

        $category = $this->fetchCategory($categoryId, $salesChannelContext->getContext());
        if (is_null($category)) {
            throw new StorefrontException(Response::HTTP_BAD_REQUEST, 'PRODUCT_FINDER_CATEGORY_NOT_FOUND', 'Category not found');
        }

        $criteria = new Criteria();
        $criteria->setTitle('cms::product-listing');

        $listing = $this->listingRoute
            ->load($categoryId, $request, $salesChannelContext, $criteria)
            ->getResult();

        $appliedPropertyOptionsIds = $listing->getCurrentFilters()['properties'] ?? [];

        $appliedPropertyOptions = !empty($appliedPropertyOptionsIds) ? $this->fetchOptions($appliedPropertyOptionsIds, $salesChannelContext->getContext()) : [];

        return $this->renderStorefront('@WebwirkungProductFinder/storefront/component/product/product-finder-listing.html.twig', [
            'searchResult' => $listing,
            'appliedPropertyOptions' => $appliedPropertyOptions,
            'dataUrl' => $this->generateUrl('widgets.search.pagelet.v2'),
            'filterUrl' => $this->generateUrl('widgets.search.filter'),
            'params' => null,
            'sidebar' => 0,
            'boxLayout' => 'minimal',
            'displayMode' => '',
            'listingColumns' => 'col-sm-6 col-lg-4 col-xl-3',
        ]);
    }

    private function fetchPropertyGroups(array $propertyGroupIds, Context $context): array
    {
        $criteria = new Criteria($propertyGroupIds);
        $criteria->addAssociation('options.media');

        $propertyGroups = $this->propertyGroupRepository->search($criteria, $context);


        return array_values($propertyGroups->map(fn(PropertyGroupEntity $propertyGroupEntity) => [
            'id' => $propertyGroupEntity->getId(),
            'image' => isset($propertyGroupEntity->getCustomFields()[PluginLifecycle::WW_PRODUCT_FINDER_PROPERTY_GROUP_IMAGE]) ?
                $this->getMedia($propertyGroupEntity->getCustomFields()[PluginLifecycle::WW_PRODUCT_FINDER_PROPERTY_GROUP_IMAGE], $context)?->getUrl() : '',
            'label' => $propertyGroupEntity->getCustomFields()[PluginLifecycle::WW_PRODUCT_FINDER_PROPERTY_GROUP_LABEL] ?? '',
            'name' => $propertyGroupEntity->getTranslation('name'),
            'options' => array_values($propertyGroupEntity->getOptions()->map(static fn(PropertyGroupOptionEntity $option) => [
                'id' => $option->getId(),
                'image' => $option->getMedia()?->getUrl() ?? '',
                'name' => $option->getTranslation('name')
            ]))
        ]
        ));
    }

    private function fetchCategory(string $categoryId, Context $context): ?CategoryEntity
    {
        return $this->categoryRepository->search(new Criteria([$categoryId]), $context)->first();
    }

    private function fetchOptions(array $optionsIds, Context $context): array
    {
        $criteria = new Criteria($optionsIds);
        $criteria->addAssociation('media');

        return $this->propertyGroupOptionRepository->search($criteria, $context)->getElements();
    }

    private function getMedia(string $mediaId, Context $context): ?MediaEntity
    {
        return $this->mediaRepository->search(new Criteria([$mediaId]), $context)->first();
    }
}