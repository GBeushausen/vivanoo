<?php

declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Cart;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartBehavior;
use Shopware\Core\Checkout\Cart\CartDataCollectorInterface;
use Shopware\Core\Checkout\Cart\CartProcessorInterface;
use Shopware\Core\Checkout\Cart\Event\CartLoadedEvent;
use Shopware\Core\Checkout\Cart\LineItem\CartDataCollection;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class AddPropertiesCartProcessor implements CartProcessorInterface, CartDataCollectorInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            CartLoadedEvent::class => 'onCartLoaded'
        ];
    }

    public function collect(CartDataCollection $data, Cart $original, SalesChannelContext $context, CartBehavior $behavior): void
    {
        $lineItems = $original->getLineItems();
        foreach($lineItems as $lineItem) {
            if ($lineItem->getType() === 'product' || $lineItem->getType() === 'custom-product'){
                $productId = $lineItem->getReferencedId();
                if ($data->has('product-' . $productId)) {
                    $product = $data->get('product-' . $productId);
                    $properties = $product->getProperties();
                    $propertiesData = [];
                    foreach ($properties as $property) {
                        $propertyId = $property->getGroup()->getId();
                        $propertyGroupName = $property->getGroup()->getName();
                        $propertyName = $property->getName();
                        $propertyValue = $property->getColorHexCode() ? $property->getColorHexCode() : $property->getName();

                        $propertiesData[$propertyId] = [
                            'label' => $propertyGroupName,
                            'name' => $propertyName,
                            'value' => $propertyValue
                        ];
                    }
                    $lineItem->setPayloadValue('properties', $propertiesData);
                }
            }
        }
    }
    public function process(CartDataCollection $data, Cart $original, Cart $toCalculate, SalesChannelContext $context, CartBehavior $behavior): void
    {

    }

}