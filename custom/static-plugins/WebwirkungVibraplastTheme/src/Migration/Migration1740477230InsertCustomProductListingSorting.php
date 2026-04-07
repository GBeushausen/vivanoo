<?php
declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

class Migration1740477230InsertCustomProductListingSorting extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1740477230;
    }

    public function update(Connection $connection): void
    {
        $sortingId = Uuid::randomBytes();
        $fields = [
            [
                'field' => 'product.name',
                'order' => 'asc',
                'priority' => 1,
                'naturalSorting' => 0,
            ]
        ];

        $connection->insert('product_sorting', [
            'id' => $sortingId,
            'url_key' => 'pim-sorting',
            'fields' => json_encode($fields, \JSON_THROW_ON_ERROR),
            'active' => true,
            'priority' => 1,
            'locked' => 0,
            'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);

        $languageId = $connection->fetchFirstColumn('SELECT id FROM language WHERE `name` = "Deutsch"')[0];
        $connection->insert('product_sorting_translation', [
            'product_sorting_id' => $sortingId,
            'language_id' => $languageId,
            'label' => 'PIM sorting',
            'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);

        $languageId = $connection->fetchFirstColumn('SELECT id FROM language WHERE `name` = "Francaise"')[0];
        $connection->insert('product_sorting_translation', [
            'product_sorting_id' => $sortingId,
            'language_id' => $languageId,
            'label' => 'PIM sorting',
            'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);

        $languageId = $connection->fetchFirstColumn('SELECT id FROM language WHERE `name` = "English"')[0];
        $connection->insert('product_sorting_translation', [
            'product_sorting_id' => $sortingId,
            'language_id' => $languageId,
            'label' => 'PIM sorting',
            'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
