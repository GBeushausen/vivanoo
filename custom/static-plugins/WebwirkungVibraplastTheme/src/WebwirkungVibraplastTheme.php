<?php declare(strict_types=1);

namespace Webwirkung\VibraplastTheme;

use Exception;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UpdateContext;
use Shopware\Storefront\Framework\ThemeInterface;
use Webwirkung\VibraplastTheme\Installer\PluginLifecycle;

class WebwirkungVibraplastTheme extends Plugin implements ThemeInterface
{
    public function getThemeConfigPath(): string
    {
        return 'theme.json';
    }


    /**
     * @throws Exception
     */
    public function install(InstallContext $installContext): void
    {
        /** @var EntityRepository $customFieldSetRepository * */
        $customFieldSetRepository = $this->container->get('custom_field_set.repository');

        (new PluginLifecycle($customFieldSetRepository))
            ->install($installContext);
    }

    /**
     * @throws Exception
     */
    public function update(UpdateContext $updateContext): void
    {
        /** @var EntityRepository $customFieldSetRepository * */
        $customFieldSetRepository = $this->container->get('custom_field_set.repository');

        (new PluginLifecycle($customFieldSetRepository))
            ->update($updateContext);
    }
}