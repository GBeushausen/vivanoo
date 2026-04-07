<?php

declare(strict_types=1);

namespace Webwirkung\ProductFinder\Installer;

use Exception;
use Shopware\Core\Content\Category\CategoryDefinition;
use Shopware\Core\Content\Property\PropertyGroupDefinition;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UpdateContext;
use Shopware\Core\System\CustomField\CustomFieldTypes;

class PluginLifecycle
{
    public const WW_PRODUCT_FINDER_CATEGORY_SET = 'ww_product_finder_category_set';
    public const WW_PRODUCT_FINDER_PROPERTY_GROUP_SET = 'ww_product_finder_property_group_set';
    public const WW_PRODUCT_FINDER_STEP_1_PROPERTY_GROUPS = 'ww_product_finder_step_1_property_groups';
    public const WW_PRODUCT_FINDER_STEP_2_PROPERTY_GROUPS = 'ww_product_finder_step_2_property_groups';
    public const WW_PRODUCT_FINDER_STEP_3_PROPERTY_GROUPS = 'ww_product_finder_step_3_property_groups';

    public const WW_PRODUCT_FINDER_STEP_1_QUESTION = 'ww_product_finder_step_1_question';
    public const WW_PRODUCT_FINDER_STEP_1_QUESTION_2 = 'ww_product_finder_step_1_question_2';
    public const WW_PRODUCT_FINDER_STEP_2_QUESTION = 'ww_product_finder_step_2_question';
    public const WW_PRODUCT_FINDER_STEP_3_QUESTION = 'ww_product_finder_step_3_question';

    public const WW_PRODUCT_FINDER_STEP_1_LABEL = 'ww_product_finder_step_1_label';
    public const WW_PRODUCT_FINDER_STEP_2_LABEL = 'ww_product_finder_step_2_label';
    public const WW_PRODUCT_FINDER_STEP_3_LABEL = 'ww_product_finder_step_3_label';

    public const WW_PRODUCT_FINDER_PROPERTY_GROUP_LABEL = 'ww_product_finder_property_group_label';

    public const WW_PRODUCT_FINDER_PROPERTY_GROUP_IMAGE = 'ww_product_finder_property_group_image';


    public function __construct(
        private readonly EntityRepository $customFieldSetRepository,
        private readonly EntityRepository $customFieldRepository
    )
    {
    }

    /**
     * @throws Exception
     */
    public function install(InstallContext $context): void
    {
        $this->updateTo100($context->getContext());
        $this->updateTo101($context->getContext());
    }

    /**
     * @throws Exception
     */
    public function update(UpdateContext $updateContext): void
    {
        if (version_compare($updateContext->getCurrentPluginVersion(), '1.0.0', '<')) {
            $this->updateTo100($updateContext->getContext());
        }
        if (version_compare($updateContext->getCurrentPluginVersion(), '1.0.1', '<')) {
            $this->updateTo101($updateContext->getContext());
        }
    }

    private function updateTo100(Context $context): void
    {
        $this->customFieldSetRepository->upsert([
            [
                'name' => self::WW_PRODUCT_FINDER_CATEGORY_SET,
                'config' => [
                    'label' => [
                        'en-GB' => 'Product Finder category set',
                        'de-DE' => 'Produktfinder Kategorie Set',
                    ],
                    "translated" => true,
                ],
                'relations' => [
                    ['entityName' => CategoryDefinition::ENTITY_NAME],
                ],
                'customFields' => [
                    [
                        'name' => self::WW_PRODUCT_FINDER_STEP_1_PROPERTY_GROUPS,
                        'type' => CustomFieldTypes::ENTITY,
                        'config' => [
                            "customFieldType" => CustomFieldTypes::ENTITY,
                            "customFieldPosition" => 1,
                            "componentName" => "sw-entity-multi-id-select",
                            "label" => [
                                "en-GB" => "Step 1 property groups",
                                "de-DE" => "Schritt 1 Eigenschaftsgruppen"
                            ],
                            "entity" => PropertyGroupDefinition::ENTITY_NAME
                        ]
                    ],
                    [
                        'name' => self::WW_PRODUCT_FINDER_STEP_2_PROPERTY_GROUPS,
                        'type' => CustomFieldTypes::ENTITY,
                        'config' => [
                            "customFieldType" => CustomFieldTypes::ENTITY,
                            "customFieldPosition" => 2,
                            "componentName" => "sw-entity-multi-id-select",
                            "label" => [
                                "en-GB" => "Step 2 property groups",
                                "de-DE" => "Schritt 2 Eigenschaftsgruppen"
                            ],
                            "entity" => PropertyGroupDefinition::ENTITY_NAME
                        ]
                    ],
                    [
                        'name' => self::WW_PRODUCT_FINDER_STEP_3_PROPERTY_GROUPS,
                        'type' => CustomFieldTypes::ENTITY,
                        'config' => [
                            "customFieldType" => CustomFieldTypes::ENTITY,
                            "customFieldPosition" => 3,
                            "componentName" => "sw-entity-multi-id-select",
                            "label" => [
                                "en-GB" => "Step 3 property groups",
                                "de-DE" => "Schritt 3 Eigenschaftsgruppen"
                            ],
                            "entity" => PropertyGroupDefinition::ENTITY_NAME
                        ]
                    ],
                    [
                        'name' => self::WW_PRODUCT_FINDER_STEP_1_QUESTION,
                        'type' => CustomFieldTypes::TEXT,
                        'config' => [
                            'label' => [
                                'en-GB' => 'Step 1 question',
                                'de-DE' => 'Schritt 1 Frage'
                            ],
                            'customFieldPosition' => 5,
                            'type' => CustomFieldTypes::TEXT,
                            'componentName' => 'sw-field',
                            'customFieldType' => CustomFieldTypes::TEXT
                        ]
                    ],
                    [
                        'name' => self::WW_PRODUCT_FINDER_STEP_2_QUESTION,
                        'type' => CustomFieldTypes::TEXT,
                        'config' => [
                            'label' => [
                                'en-GB' => 'Step 2 question',
                                'de-DE' => 'Schritt 2 Frage'
                            ],
                            'customFieldPosition' => 6,
                            'type' => CustomFieldTypes::TEXT,
                            'componentName' => 'sw-field',
                            'customFieldType' => CustomFieldTypes::TEXT
                        ]
                    ],
                    [
                        'name' => self::WW_PRODUCT_FINDER_STEP_3_QUESTION,
                        'type' => CustomFieldTypes::TEXT,
                        'config' => [
                            'label' => [
                                'en-GB' => 'Step 3 question',
                                'de-DE' => 'Schritt 3 Frage'
                            ],
                            'customFieldPosition' => 7,
                            'type' => CustomFieldTypes::TEXT,
                            'componentName' => 'sw-field',
                            'customFieldType' => CustomFieldTypes::TEXT
                        ]
                    ],
                    [
                        'name' => self::WW_PRODUCT_FINDER_STEP_1_LABEL,
                        'type' => CustomFieldTypes::TEXT,
                        'config' => [
                            'label' => [
                                'en-GB' => 'Step 1 label',
                                'de-DE' => 'Schritt 1 Label'
                            ],
                            'customFieldPosition' => 8,
                            'type' => CustomFieldTypes::TEXT,
                            'componentName' => 'sw-field',
                            'customFieldType' => CustomFieldTypes::TEXT
                        ]
                    ],
                    [
                        'name' => self::WW_PRODUCT_FINDER_STEP_2_LABEL,
                        'type' => CustomFieldTypes::TEXT,
                        'config' => [
                            'label' => [
                                'en-GB' => 'Step 2 label',
                                'de-DE' => 'Schritt 2 Label'
                            ],
                            'customFieldPosition' => 9,
                            'type' => CustomFieldTypes::TEXT,
                            'componentName' => 'sw-field',
                            'customFieldType' => CustomFieldTypes::TEXT
                        ]
                    ],
                    [
                        'name' => self::WW_PRODUCT_FINDER_STEP_3_LABEL,
                        'type' => CustomFieldTypes::TEXT,
                        'config' => [
                            'label' => [
                                'en-GB' => 'Step 3 label',
                                'de-DE' => 'Schritt 3 Label'
                            ],
                            'customFieldPosition' => 10,
                            'type' => CustomFieldTypes::TEXT,
                            'componentName' => 'sw-field',
                            'customFieldType' => CustomFieldTypes::TEXT
                        ]
                    ],
                ],
            ],
            [
                'name' => self::WW_PRODUCT_FINDER_PROPERTY_GROUP_SET,
                'config' => [
                    'label' => [
                        'en-GB' => 'Product Finder property group set',
                        'de-DE' => 'Produktfinder Eigenschaftsgruppen Set',
                    ],
                    "translated" => true,
                ],
                'relations' => [
                    ['entityName' => PropertyGroupDefinition::ENTITY_NAME],
                ],
                'customFields' => [
                    [
                        'name' => self::WW_PRODUCT_FINDER_PROPERTY_GROUP_LABEL,
                        'type' => CustomFieldTypes::TEXT,
                        'config' => [
                            'label' => [
                                'en-GB' => 'Property group label',
                                'de-DE' => 'Eigenschaftsgruppen Label'
                            ],
                            'customFieldPosition' => 1,
                            'type' => CustomFieldTypes::TEXT,
                            'componentName' => 'sw-field',
                            'customFieldType' => CustomFieldTypes::TEXT
                        ]
                    ],
                    [
                        'name' => self::WW_PRODUCT_FINDER_PROPERTY_GROUP_IMAGE,
                        'type' => CustomFieldTypes::MEDIA,
                        'config'=>[
                            "customFieldType" => CustomFieldTypes::MEDIA,
                            "customFieldPosition" => 1,
                            "label" => [
                                "en-GB" => "Property group image",
                                "de-DE" => "Eigenschaftsgruppen Bild"
                            ],
                            "componentName" => "sw-media-field"
                        ]
                    ]
                ]
            ],

        ], $context);
    }


    /**
     * @throws Exception
     */
    private function updateTo101(Context $context): void
    {
        $productFinderCategorySetId = $this->customFieldSetRepository->searchIds((new Criteria())->addFilter(new EqualsFilter('name', self::WW_PRODUCT_FINDER_CATEGORY_SET)), $context)->firstId();
        if (is_null($productFinderCategorySetId)) {
            throw new Exception('Custom field set for product finder category was not found');
        }

        $this->customFieldRepository->upsert([
            [
                'customFieldSetId' => $productFinderCategorySetId,
                'name' => self::WW_PRODUCT_FINDER_STEP_1_QUESTION_2,
                'type' => CustomFieldTypes::TEXT,
                'config' => [
                    'label' => [
                        'en-GB' => 'Step 1 question 2',
                        'de-DE' => 'Schritt 1 Frage 2'
                    ],
                    'customFieldPosition' => 5.5,
                    'type' => CustomFieldTypes::TEXT,
                    'componentName' => 'sw-field',
                    'customFieldType' => CustomFieldTypes::TEXT
                ]
            ],
        ], $context);

    }
}