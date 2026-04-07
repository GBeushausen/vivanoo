<?php
declare(strict_types=1);

namespace Webwirkung\MinimumOrderSurchargePlugin\Core\Checkout;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartBehavior;
use Shopware\Core\Checkout\Cart\CartDataCollectorInterface;
use Shopware\Core\Checkout\Cart\LineItem\CartDataCollection;
use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Framework\Struct\ArrayStruct;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Webwirkung\MinimumOrderSurchargePlugin\Plugin\Config;
use Webwirkung\MinimumOrderSurchargePlugin\Service\CustomerCustomFieldsInstaller;

class MinimumOrderSurchargeCollector implements CartDataCollectorInterface
{
    public function __construct(
        private Config $config,
    )
    {
    }

    public function collect(CartDataCollection $data, Cart $original, SalesChannelContext $context, CartBehavior $behavior): void
    {
        $extensionName = self::buildMinimumSurchargeExtensionName($context);
        if ($original->hasExtension($extensionName)) {
            return;
        }

        $original->addExtension($extensionName, new ArrayStruct(['value' => $this->getMinimalOrderValue($context)]));
    }

    private function getMinimalOrderValue(SalesChannelContext $context): float
    {
        try {
            return $this->getCustomerMinimalOrderValue($context);
        } catch (\Exception) {
        }

        if ($context->getCurrentCustomerGroup()->getDisplayGross()) {
            return $this->config->getMinimalOrderValueGross();
        }

        return $this->config->getMinimalOrderValueNet();
    }

    /**
     * @throws \Exception
     */
    private function getCustomerMinimalOrderValue(SalesChannelContext $context): float
    {
        $customer = $context->getCustomer();

        if ($customer === null) {
            throw new \Exception('Customer does not exist');
        }

        $group = $customer->getGroup() ?? $context->getCurrentCustomerGroup();

        if ($group->getDisplayGross()) {
            return $this->getCustomerGrossValue($customer);
        }

        return $this->getCustomerNetValue($customer);
    }

    private function getCustomerNetValue(CustomerEntity $customer): float
    {
        $customFields = $customer->getCustomFields() ?? [];
        $fieldName = CustomerCustomFieldsInstaller::MINIMAL_ORDER_VALUE_NET_FIELD_NAME;
        $value = $customFields[$fieldName] ?? throw new \Exception('Invalid Minimal order value (net)');

        return (float)$value;
    }

    private function getCustomerGrossValue(CustomerEntity $customer): float
    {
        $customFields = $customer->getCustomFields() ?? [];
        $fieldName = CustomerCustomFieldsInstaller::MINIMAL_ORDER_VALUE_GROSS_FIELD_NAME;
        $value = $customFields[$fieldName] ?? throw new \Exception('Invalid Minimal order value (gross)');

        return (float)$value;
    }

    public static function buildMinimumSurchargeExtensionName(SalesChannelContext $context): string
    {
        return sprintf('minimumOrderSurchargeAmount_%s_%s', $context->getToken(), $context->getCustomerId() ?? 'anonymous');
    }
}
