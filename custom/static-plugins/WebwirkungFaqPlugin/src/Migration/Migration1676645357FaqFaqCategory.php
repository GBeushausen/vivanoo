<?php declare(strict_types=1);

namespace Webwirkung\FaqPlugin\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1676645357FaqFaqCategory extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1676645357;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
            CREATE TABLE IF NOT EXISTS `ww_faq_faq_category` (
                `faq_id` BINARY(16) NOT NULL,
                `faq_category_id` BINARY(16) NOT NULL,
                `created_at` DATETIME(3) NOT NULL,
                PRIMARY KEY (`faq_id`,`faq_category_id`),

                CONSTRAINT `fk.ww_faq_faq_category.faq_id` FOREIGN KEY (`faq_id`)
                    REFERENCES `ww_faq` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.ww_faq_faq_category.faq_category_id` FOREIGN KEY (`faq_category_id`)
                    REFERENCES `ww_faq_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) 
                ENGINE=InnoDB 
                DEFAULT CHARSET=utf8mb4 
                COLLATE=utf8mb4_unicode_ci;
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
