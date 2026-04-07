<?php

declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Checkout\Cart;

use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\LineItemFactoryHandler\LineItemFactoryInterface;
use Shopware\Core\Checkout\Cart\PriceDefinitionFactory;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class CustomProductLineItemFactory implements LineItemFactoryInterface
{
    public const CUSTOM_PRODUCT_LINE_ITEM_TYPE = 'custom-product';

    public function __construct(
        private readonly PriceDefinitionFactory $priceDefinitionFactory,
        private readonly EntityRepository       $mediaRepository
    )
    {
    }

    public function supports(string $type): bool
    {
        return $type === self::CUSTOM_PRODUCT_LINE_ITEM_TYPE;
    }

    public function create(array $data, SalesChannelContext $context): LineItem
    {
        $lineItem = new LineItem(Uuid::randomHex(), $data['type'], $data['referencedId'] ?? null, $data['quantity'] ?? 1);
        $lineItem->markModified();

        $this->update($lineItem, $data, $context);

        return $lineItem;
    }

    public function update(LineItem $lineItem, array $data, SalesChannelContext $context): void
    {
        if (isset($data['payload'])) {
            $lineItem->setPayload($data['payload'] ?? []);
        }
        if (isset($data['referencedId'])) {
            $lineItem->setReferencedId($data['referencedId']);
        }

        if (isset($data['configuratorOptions'])) {
            $lineItem->setPayloadValue('configuratorOptions', $data['configuratorOptions']);
        }

        if (isset($data['variantProperty'])) {
            $lineItem->setPayloadValue('variantProperty', $data['variantProperty']);
        }

        $lineItem->setStackable(true);

        if (isset($data['removable'])) {
            $lineItem->setRemovable($data['removable']);
        }

        if (isset($data['label'])) {
            $lineItem->setLabel($data['label']);
        }

        if (isset($data['description'])) {
            $lineItem->setDescription($data['description']);
        }

        if (isset($data['coverId'])) {
            $cover = $this->mediaRepository->search(new Criteria([$data['coverId']]), $context->getContext())->first();

            $lineItem->setCover($cover);
        }

        if (isset($data['priceDefinition'])) {
            $lineItem->setPriceDefinition($this->priceDefinitionFactory->factory($context->getContext(), $data['priceDefinition'], $data['type']));
        }
    }
}