<?php declare(strict_types=1);

namespace Webwirkung\GlossaryPlugin\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1709041151GlossaryTranslation extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1709041151;
    }

    public function update(Connection $connection): void
    {
        $query = <<<SQL
        CREATE TABLE IF NOT EXISTS `ww_glossary_translation` (
            `ww_glossary_id` BINARY(16) NOT NULL,
            `language_id` BINARY(16) NOT NULL,
            `name` VARCHAR(255),
            `description` LONGTEXT COLLATE utf8mb4_unicode_ci NULL,
            `created_at` DATETIME(3) NOT NULL,
            `updated_at` DATETIME(3) NULL,
            PRIMARY KEY (`ww_glossary_id`, `language_id`),
            CONSTRAINT `fk.ww_glossary_translation.ww_glossary_id` FOREIGN KEY (`ww_glossary_id`)
                REFERENCES `ww_glossary` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT `fk.ww_glossary_translation.language_id` FOREIGN KEY (`language_id`)
                REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
        )
            ENGINE = InnoDB
            DEFAULT CHARSET = utf8mb4
            COLLATE = utf8mb4_unicode_ci;
        SQL;
        $connection->executeStatement($query);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
