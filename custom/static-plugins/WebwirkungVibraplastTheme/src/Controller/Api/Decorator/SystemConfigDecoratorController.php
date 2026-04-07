<?php

namespace Webwirkung\VibraplastTheme\Controller\Api\Decorator;

use Shopware\Core\Framework\Context;
use Shopware\Core\System\SystemConfig\Api\SystemConfigController;
use Shopware\Core\System\SystemConfig\Service\ConfigurationService;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Core\System\SystemConfig\Validation\SystemConfigValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SystemConfigDecoratorController extends SystemConfigController
{
    public function __construct(
        readonly ConfigurationService $configurationService,
        private readonly SystemConfigService $systemConfig,
        private readonly SystemConfigValidator $systemConfigValidator
    ) {
        parent::__construct(
            $configurationService,
            $systemConfig,
            $systemConfigValidator,
        );
    }

    #[Route(path: '/api/_action/system-config/batch', name: 'api.action.core.save.system-config.batch', defaults: ['_acl' => ['system_config:update', 'system_config:create', 'system_config:delete']], methods: ['POST'])]
    public function batchSaveConfiguration(Request $request, Context $context): JsonResponse
    {
        /*
         * Start decoration
         * @see https://3.basecamp.com/4942507/buckets/25490537/card_tables/cards/7945887162
         */
        $data = $request->request->all();
        $data = reset($data);
        unset($data['core.app.shopId.value']);

        $data = [
            'null' => $data,
        ];
        // End decoration

        $this->systemConfigValidator->validate($data, $context);

        /**
         * @var string               $salesChannelId
         * @var array<string, mixed> $kvs
         */
        foreach ($request->request->all() as $salesChannelId => $kvs) {
            if ($salesChannelId === 'null') {
                $salesChannelId = null;
            }

            $this->systemConfig->setMultiple($kvs, $salesChannelId);
        }

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
