<?php declare(strict_types=1);

namespace Webwirkung\FaqPlugin\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1716451203FaqCategoryTranslation extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1716451203;
    }

    public function update(Connection $connection): void
    {
        $query = <<<SQL
        CREATE TABLE IF NOT EXISTS `ww_faq_category_translation` (
            `ww_faq_category_id` BINARY(16) NOT NULL,
            `language_id` BINARY(16) NOT NULL,
            `name` VARCHAR(255) NULL,
            `created_at` DATETIME(3) NOT NULL,
            `updated_at` DATETIME(3) NULL,
            PRIMARY KEY (`ww_faq_category_id`, `language_id`),
            CONSTRAINT `fk.ww_faq_category_translation.ww_faq_category_id` FOREIGN KEY (`ww_faq_category_id`)
                REFERENCES `ww_faq_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT `fk.ww_faq_category_translation.language_id` FOREIGN KEY (`language_id`)
                REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
        )
            ENGINE = InnoDB
            DEFAULT CHARSET = utf8mb4
            COLLATE = utf8mb4_unicode_ci;
        SQL;
        $connection->executeStatement($query);
    }
}
