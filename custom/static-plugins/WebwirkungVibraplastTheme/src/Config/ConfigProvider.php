<?php

declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Config;

use Shopware\Core\System\SystemConfig\SystemConfigService;

class ConfigProvider
{

    public function __construct(private readonly SystemConfigService $systemConfigService)
    {

    }

    public function getLengthPropertyId(?string $salesChannelId = null): string
    {
        return $this->systemConfigService->getString('WebwirkungVibraplastTheme.config.lengthProperty', $salesChannelId);
    }

    public function getHeightPropertyId(?string $salesChannelId = null): string
    {
        return $this->systemConfigService->getString('WebwirkungVibraplastTheme.config.heightProperty', $salesChannelId);
    }

    public function getWidthPropertyId(?string $salesChannelId = null): string
    {
        return $this->systemConfigService->getString('WebwirkungVibraplastTheme.config.widthProperty', $salesChannelId);
    }

    public function getColorPropertyId(?string $salesChannelId = null): string
    {
        return $this->systemConfigService->getString('WebwirkungVibraplastTheme.config.colorProperty', $salesChannelId);
    }

    public function getProductDetailTabProperties(?string $salesChannelId = null): array
    {
        $sorting = $this->systemConfigService->get('WebwirkungVibraplastTheme.config.productDetailTabProperties', $salesChannelId);
        return is_array($sorting) ? $sorting : [];
    }

    public function getTechnicalDataPropertySet(?string $salesChannelId = null): ?string
    {
        return $this->systemConfigService->getString('WebwirkungVibraplastTheme.config.technicalDataPropertySet', $salesChannelId) ?: null;
    }

    public function isTechnicalDataTabEnabled(?string $salesChannelId = null): bool
    {
        return $this->systemConfigService->getBool('WebwirkungVibraplastTheme.config.enableTechnicalDataTab', $salesChannelId);
    }
}
