<?php

declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Subscriber;

use Shopware\Core\Content\Property\Aggregate\PropertyGroupOption\PropertyGroupOptionEntity;
use Shopware\Core\Content\Property\PropertyGroupEntity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Struct\ArrayEntity;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Webwirkung\VibraplastTheme\Config\ConfigProvider;

class ProductDetailPageLoadedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly EntityRepository $propertySetRepository,
        private readonly ConfigProvider   $configProvider,
        private readonly EntityRepository $productRepository
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProductPageLoadedEvent::class => 'onProductLoaded',
        ];
    }

    public function onProductLoaded(ProductPageLoadedEvent $event): void
    {
        $product = $event->getPage()->getProduct();
        $properties = $product->getProperties();

        $this->mergeParentProductProperties($product, $properties, $event);

        $propertyGroupIds = [];
        foreach ($properties as $property) {
            $propertyGroupIds[] = $property->getGroup()->getId();
        }

        $propertySetCriteria = new Criteria();
        $propertySetCriteria
            ->addAssociation('propertyGroups.id')
            ->addFilter(new EqualsAnyFilter('propertyGroups.id', $propertyGroupIds));

        $propertySets = $this->propertySetRepository->search($propertySetCriteria, $event->getContext())->getEntities();
        $propertySetSortingConfig = $this->configProvider->getProductDetailTabProperties($event->getSalesChannelContext()->getSalesChannelId());

        $groupedProperties = [];
        foreach ($properties as $property) {
            $propertyGroupId = $property->getGroup()->getId();
            foreach ($propertySets as $propertySet) {
                if (!in_array($propertySet->getId(), $propertySetSortingConfig)) {
                    continue;
                }

                foreach ($propertySet->getPropertyGroups() as $propertyGroup) {
                    if ($propertyGroupId === $propertyGroup->getId()) {
                        $groupId = $propertySet->getId();
                        if (!isset($groupedProperties[$groupId])) {
                            $sortingPosition = array_search($propertySet->getId(), $propertySetSortingConfig);
                            $sortingPosition = $sortingPosition !== false ? $sortingPosition : 999;

                            $groupedProperties[$groupId] = [
                                'id' => $propertySet->getId(),
                                'name' => $propertySet->getName(),
                                'groups' => [],
                                'sorting' => $sortingPosition,
                            ];
                        }

                        $this->addPropertyToPropertySet($groupedProperties[$groupId], $property, $propertyGroup);
                        break;
                    }
                }
            }
        }

        $technicalDataPropertySetId = $this->configProvider->getTechnicalDataPropertySet($event->getSalesChannelContext()->getSalesChannelId());

        // Filter the technical data property set from existing $propertySets instead of fetching separately
        $technicalDataPropertySet = null;
        if ($technicalDataPropertySetId) {
            // Find the technical data property set in the already fetched $propertySets
            $technicalDataPropertySetEntity = null;
            foreach ($propertySets as $propertySet) {
                if ($propertySet->getId() === $technicalDataPropertySetId) {
                    $technicalDataPropertySetEntity = $propertySet;
                    break;
                }
            }

            if ($technicalDataPropertySetEntity) {
                $technicalDataPropertySet = $this->processPropertySetProperties($properties->getElements(), $technicalDataPropertySetEntity);
            }
        }

        $propertyGroupsData = [
            "WwPropertyGroups" => $groupedProperties,
            "TechnicalDataPropertySet" => $technicalDataPropertySet,
        ];

        $event->getPage()->addExtension('ww_property_groups', new ArrayEntity($propertyGroupsData));
    }

    private function mergeParentProductProperties($product, $properties, ProductPageLoadedEvent $event): void
    {
        if ($product->getParentId() !== null) {
            $parentCriteria = new Criteria([$product->getParentId()]);
            $parentCriteria->addAssociation('properties.group');
            $parentProduct = $this->productRepository->search($parentCriteria, $event->getContext())->first();

            if ($parentProduct !== null) {
                $parentProperties = $parentProduct->getProperties();
                if ($parentProperties !== null) {
                    $properties->merge($parentProperties);
                }
            }
        }
    }

    private function addPropertyToPropertySet(array &$propertySetData, PropertyGroupOptionEntity $property, PropertyGroupEntity $propertyGroup): void
    {
        // Check if the property group already exists
        $groupExists = false;
        foreach ($propertySetData['groups'] as &$group) {
            if ($group['name'] === $propertyGroup->getName()) {
                // Add the property to the existing property group
                $propertyName = $property->getTranslated()['name'] ?? $property->getName();
                $propertyPosition = $property->getPosition();
                $group['values'][] = [
                    'name' => $propertyName,
                    'position' => $propertyPosition,
                ];
                $group['position'] = $propertyGroup->getPosition();
                $groupExists = true;
                break;
            }
        }

        // If the property group does not exist, add it to the property set
        if (!$groupExists) {
            $propertyName = $property->getTranslated()['name'] ?? $property->getName();
            $propertyPosition = $property->getPosition();

            $propertySetData['groups'][] = [
                'name' => $propertyGroup->getName(),
                'customFields' => $propertyGroup->getCustomFields(),
                'values' => [
                    [
                        'name' => $propertyName,
                        'position' => $propertyPosition,
                    ],
                ],
                'position' => $propertyGroup->getPosition(),
            ];
        }
    }

    private function processPropertySetProperties(array $properties, object $propertySetEntity): array
    {
        $propertySetData = [
            'id' => $propertySetEntity->getId(),
            'name' => $propertySetEntity->getName(),
            'groups' => [],
        ];

        foreach ($properties as $property) {
            $propertyGroupId = $property->getGroup()->getId();

            foreach ($propertySetEntity->getPropertyGroups() as $propertyGroup) {
                if ($propertyGroupId === $propertyGroup->getId()) {
                    $this->addPropertyToPropertySet($propertySetData, $property, $propertyGroup);
                    break;
                }
            }
        }

        return $propertySetData;
    }

}