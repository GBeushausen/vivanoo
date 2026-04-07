<?php declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;


class Migration1759135117AddWwVariantTableSortingProperty extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1759135117;
    }

    public function update(Connection $connection): void
    {
        $setId = $connection->fetchOne(
            "SELECT id FROM custom_field_set WHERE name = 'ww_product_display'"
        );

        if (!$setId) {
            throw new \RuntimeException('Custom field set "ww_product_display" not found');
        }

        $config = [
            'componentName' => 'sw-entity-single-select',
            'entity' => 'property_group',
            'customFieldType' => 'entity',
            'customFieldPosition' => 2,
            'label' => [
                'en-GB' => 'Variant table sorting property',
                'de-DE' => 'Variantentabelle Sortierung Eigenschaft',
            ],
            'placeholder' => [
                'en-GB' => null,
            ],
            'helpText' => [
                'en-GB' => null,
            ],
        ];

        $connection->insert('custom_field', [
            'id' => Uuid::randomBytes(),
            'name' => 'ww_variant_table_sorting_property',
            'type' => 'select',
            'config' => json_encode($config, \JSON_THROW_ON_ERROR),
            'active' => true,
            'set_id' => $setId,
            'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
