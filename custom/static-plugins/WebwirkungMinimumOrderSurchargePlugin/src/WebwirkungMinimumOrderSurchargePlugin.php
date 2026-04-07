<?php declare(strict_types=1);

namespace Webwirkung\MinimumOrderSurchargePlugin;

use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Webwirkung\MinimumOrderSurchargePlugin\Service\CustomerCustomFieldsInstaller;

class WebwirkungMinimumOrderSurchargePlugin extends Plugin
{
    public function uninstall(UninstallContext $uninstallContext): void
    {
        parent::uninstall($uninstallContext);

        if ($uninstallContext->keepUserData()) {
            return;
        }

        $customFieldsInstaller = new CustomerCustomFieldsInstaller(
            $this->container->get('custom_field.repository'),
            $this->container->get('custom_field_set.repository'),
            $this->container->get('custom_field_set_relation.repository'),
        );
        $customFieldsInstaller->uninstall('ww_customer_surcharge_set', $uninstallContext->getContext());
    }

    public function activate(ActivateContext $activateContext): void
    {
        $customFieldsInstaller = new CustomerCustomFieldsInstaller(
            $this->container->get('custom_field.repository'),
            $this->container->get('custom_field_set.repository'),
            $this->container->get('custom_field_set_relation.repository'),
        );
        $customFieldsInstaller->install('ww_customer_surcharge_set', $activateContext->getContext());
    }
}
