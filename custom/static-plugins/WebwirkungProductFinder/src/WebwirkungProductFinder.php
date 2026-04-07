<?php
declare(strict_types=1);

namespace Webwirkung\ProductFinder;

use Exception;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UpdateContext;
use Webwirkung\ProductFinder\Installer\PluginLifecycle;

class WebwirkungProductFinder extends Plugin
{

    /**
     * @throws Exception
     */
    public function install(InstallContext $installContext): void
    {
        /** @var EntityRepository $customFieldSetRepository **/
        $customFieldSetRepository = $this->container->get('custom_field_set.repository');

        (new PluginLifecycle($customFieldSetRepository))
            ->install($installContext);
    }

    /**
     * @throws Exception
     */
    public function update(UpdateContext $updateContext): void
    {
        /** @var EntityRepository $customFieldSetRepository **/
        $customFieldSetRepository = $this->container->get('custom_field_set.repository');

        /** @var EntityRepository $customFieldRepository * */
        $customFieldRepository = $this->container->get('custom_field.repository');

        (new PluginLifecycle($customFieldSetRepository, $customFieldRepository))
            ->update($updateContext);
    }
}
