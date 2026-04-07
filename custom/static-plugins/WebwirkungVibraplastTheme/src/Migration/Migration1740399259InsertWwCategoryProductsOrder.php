<?php
declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

class Migration1740399259InsertWwCategoryProductsOrder extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1740399259;
    }

    public function update(Connection $connection): void
    {
        $setId = Uuid::randomBytes();
        $config = [
            'label' => [
                'en-GB' => 'Sorting',
                'de-DE' => 'Sorting',
            ],
            'translated' => true,
        ];

        $connection->insert('custom_field_set', [
            'id' => $setId,
            'name' => 'ww_category_sorting',
            'config' => json_encode($config, \JSON_THROW_ON_ERROR),
            'active' => true,
            'position' => 1,
            'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);

        $connection->insert('custom_field_set_relation', [
            'id' => Uuid::randomBytes(),
            'set_id' => $setId,
            'entity_name' => 'category',
            'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);

        $config = [
            'componentName' => 'sw-entity-multi-id-select',
            'entity' => 'product',
            'customFieldType' => 'entity',
            'customFieldPosition' => 1,
            'label' => [
                'en-GB' => 'Default sorting order',
                'de-DE' => 'Default sorting order',
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
            'name' => 'ww_category_sorting_default_order',
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
