<?php declare(strict_types=1);

namespace Webwirkung\FaqPlugin\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1676645156FaqCategory extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1676645156;
    }

    public function update(Connection $connection): void
    {
        // implement update
        $connection->executeUpdate(
            <<<SQL
            CREATE TABLE IF NOT EXISTS `ww_faq_category` (
            `id` BINARY(16) NOT NULL,
            `created_at` DATETIME(3) NOT NULL,
            `updated_at` DATETIME(3) NULL, 
            PRIMARY KEY (`id`)  
            )
            ENGINE = InnoDB
            DEFAULT CHARSET = utf8mb4
            COLLATE = utf8mb4_unicode_ci;
        SQL
        );
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
