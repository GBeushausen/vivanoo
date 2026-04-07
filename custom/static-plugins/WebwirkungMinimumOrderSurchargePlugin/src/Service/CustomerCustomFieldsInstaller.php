<?php declare(strict_types=1);

namespace Webwirkung\MinimumOrderSurchargePlugin\Service;

use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\CustomField\Aggregate\CustomFieldSet\CustomFieldSetEntity;
use Shopware\Core\System\CustomField\CustomFieldEntity;
use Shopware\Core\System\CustomField\CustomFieldTypes;

class CustomerCustomFieldsInstaller
{
    public const MINIMAL_ORDER_VALUE_NET_FIELD_NAME = 'ww_customer_minimal_order_value_net';
    public const MINIMAL_ORDER_VALUE_GROSS_FIELD_NAME = 'ww_customer_minimal_order_value_gross';

    private const ADDITIONAL_FIELDS_FIELDSET = [
        'config' => [
            'label' => [
                'en-GB' => 'Minimum order surcharge',
                'de-DE' => 'Mindestbestellaufschlag',
                Defaults::LANGUAGE_SYSTEM => 'Minimum order surcharge'
            ]
        ],
        'customFields' => [
            [
                'name' => self::MINIMAL_ORDER_VALUE_NET_FIELD_NAME,
                'type' => CustomFieldTypes::HTML,
                'config' => [
                    'label' => [
                        'en-GB' => 'Minimal order value (net)',
                        'de-DE' => 'Mindestbestellwert (Netto)',
                        Defaults::LANGUAGE_SYSTEM => 'Minimal order value (net)'
                    ],
                    'customFieldPosition' => 1,
                ]
            ],
            [
                'name' => self::MINIMAL_ORDER_VALUE_GROSS_FIELD_NAME,
                'type' => CustomFieldTypes::HTML,
                'config' => [
                    'label' => [
                        'en-GB' => 'Minimal order value (gross)',
                        'de-DE' => 'Mindestbestellwert (Brutto)',
                        Defaults::LANGUAGE_SYSTEM => 'Minimal order value (gross)'
                    ],
                    'customFieldPosition' => 1,
                ]
            ],
        ],
    ];

    public function __construct(
        private readonly EntityRepository $customFieldRepository,
        private readonly EntityRepository $customFieldSetRepository,
        private readonly EntityRepository $customFieldSetRelationRepository,
    ) {
    }

    public function install(string $fieldSetName, Context $context): void
    {
        $fieldSet = $this->getCustomFieldSet($fieldSetName, $context);
        $fieldSetData = [
            'id' => Uuid::randomHex(),
            'name' => $fieldSetName,
            'config' => self::ADDITIONAL_FIELDS_FIELDSET['config'],
        ];

        if ($fieldSet !== null) {
            $fieldSetData['id'] = $fieldSet->getId();
        }

        $this->customFieldSetRepository->upsert([$fieldSetData],  $context);
        $fieldSetId = $fieldSetData['id'];

        if ($fieldSet === null) {
            $this->addRelation($fieldSetId, $context);
        }

        foreach (self::ADDITIONAL_FIELDS_FIELDSET['customFields'] as $customFieldData) {
            $customField = $this->getCustomField($customFieldData['name'], $context);
            $customFieldData['id'] = Uuid::randomHex();
            $customFieldData['customFieldSetId'] = $fieldSetId;

            if ($customField) {
                $customFieldData['id'] = $customField->getId();
            }

            $this->customFieldRepository->upsert([$customFieldData], $context);
        }
    }

    public function uninstall(string $fieldSetName, Context $context): void
    {
        foreach (self::ADDITIONAL_FIELDS_FIELDSET['customFields'] as $customFieldData) {
            $customField = $this->getCustomField($customFieldData['name'], $context);

            if ($customField !== null) {
                $this->customFieldRepository->delete([['id' => $customField->getId()]], $context);
            }
        }

        $fieldSet = $this->getCustomFieldSet($fieldSetName, $context);

        if ($fieldSet !== null) {
            $this->customFieldSetRepository->delete([['id' => $fieldSet->getId()]], $context);
        }
    }

    public function addRelation(string $fieldSetId, Context $context): void
    {
        $this->customFieldSetRelationRepository->upsert(
            [
                [
                    'customFieldSetId' => $fieldSetId,
                    'entityName' => 'customer',
                ]
            ],
            $context
        );
    }

    private function getCustomFieldSet(
        string $fieldSetName,
        Context $context
    ): ?CustomFieldSetEntity
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', $fieldSetName));

        return $this->customFieldSetRepository->search($criteria, $context)->first();
    }

    private function getCustomField(
        string $fieldSetName,
        Context $context
    ): ?CustomFieldEntity
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', $fieldSetName));

        return $this->customFieldRepository->search($criteria, $context)->first();
    }
}
