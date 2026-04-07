<?php declare(strict_types=1);

namespace Webwirkung\FaqPlugin;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;

class WebwirkungFaqPlugin extends Plugin
{

    public function uninstall(UninstallContext $context): void
    {
        parent::uninstall($context);

        if ($context->keepUserData()) {
            return;
        }

        $connection = $this->container->get(Connection::class);

        $connection->executeUpdate('DROP TABLE IF EXISTS `ww_faq_faq_category`');
        $connection->executeUpdate('DROP TABLE IF EXISTS `ww_faq_translation`');
        $connection->executeUpdate('DROP TABLE IF EXISTS `ww_faq_category_translation`');
        $connection->executeUpdate('DROP TABLE IF EXISTS `ww_faq`');
        $connection->executeUpdate('DROP TABLE IF EXISTS `ww_faq_category`');
    }
}