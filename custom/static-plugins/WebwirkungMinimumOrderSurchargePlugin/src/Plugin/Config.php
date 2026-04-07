<?php
declare(strict_types=1);

namespace Webwirkung\MinimumOrderSurchargePlugin\Plugin;

use Shopware\Core\System\SystemConfig\SystemConfigService;

class Config
{
    public function __construct(
        private SystemConfigService $systemConfigService,
    ) {
    }

    public function getMinimalOrderValueNet(): float
    {
        return (float) ($this->get('WebwirkungMinimumOrderSurchargePlugin.config.minimalOrderValueNet') ?? 0);
    }

    public function getMinimalOrderValueGross(): float
    {
        return (float) ($this->get('WebwirkungMinimumOrderSurchargePlugin.config.minimalOrderValueGross') ?? 0);
    }

    /**
     * @return array<mixed>|bool|float|int|string|null
     */
    private function get(string $key): mixed
    {
        return $this->systemConfigService->get($key);
    }
}
