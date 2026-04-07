<?php

declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Installer;

use Exception;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Content\Property\PropertyGroupDefinition;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UpdateContext;
use Shopware\Core\System\CustomField\CustomFieldTypes;

class PluginLifecycle
{
    public const WW_VARIANT_TABLE_PRODUCT_SET = 'ww_variant_table_product_set';
    public const WW_VARIANT_TABLE_PROPERTY_GROUP = 'ww_variant_table_property_group';
    public const WW_VARIANT_TABLE_SORTING_PROPERTY = 'ww_variant_table_sorting_property';

    


    public function __construct(
        private readonly EntityRepository $customFieldSetRepository
    )
    {
    }

    /**
     * @throws Exception
     */
    public function install(InstallContext $context): void
    {
        $this->updateTo101($context->getContext());
    }

    /**
     * @throws Exception
     */
    public function update(UpdateContext $updateContext): void
    {
        if (version_compare($updateContext->getCurrentPluginVersion(), '1.0.1', '<')) {
            $this->updateTo101($updateContext->getContext());
        }
    }

    private function updateTo101(Context $context): void
    {
        $this->customFieldSetRepository->upsert([
            [
                'name' => self::WW_VARIANT_TABLE_PRODUCT_SET,
                'config' => [
                    'label' => [
                        'en-GB' => 'Variants table',
                        'de-DE' => 'Variantentabelle',
                    ],
                    "translated" => true,
                ],
                'relations' => [
                    ['entityName' => ProductDefinition::ENTITY_NAME],
                ],
                'customFields' => [
                    [
                        'name' => self::WW_VARIANT_TABLE_PROPERTY_GROUP,
                        'type' => CustomFieldTypes::ENTITY,
                        'config' => [
                            "customFieldType" => CustomFieldTypes::ENTITY,
                            "customFieldPosition" => 1,
                            "componentName" => "sw-entity-single-select",
                            "label" => [
                                "en-GB" => "Variants table select property group",
                                "de-DE" => "Variantentabelle zur Auswahl der Eigenschaftsgruppe"
                            ],
                            "entity" => PropertyGroupDefinition::ENTITY_NAME
                        ]
                    ],
                ],
            ],

        ], $context);
    }


}