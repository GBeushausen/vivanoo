<?php

declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Decorator\Core\Checkout\Cart\Route;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartException;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\LineItem\LineItemCollection;
use Shopware\Core\Checkout\Cart\Order\OrderConverter;
use Shopware\Core\Checkout\Cart\Order\Transformer\LineItemTransformer;
use Shopware\Core\Checkout\Cart\SalesChannel\CartService;
use Shopware\Core\Checkout\Order\Aggregate\OrderLineItem\OrderLineItemCollection;
use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\Checkout\Order\OrderException;
use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductEntity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Uuid\Exception\InvalidUuidException;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\Entity\SalesChannelRepository;
use Shopware\Core\System\SalesChannel\NoContentResponse;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Swag\CustomizedProducts\Core\Checkout\Cart\Error\SwagCustomizedProductsTemplateAssignedError;
use Swag\CustomizedProducts\Core\Checkout\Cart\Route\AbstractReOrderCustomizedProductsRoute;
use Swag\CustomizedProducts\Core\Checkout\Cart\Route\ReOrderCustomizedProductsRoute;
use Swag\CustomizedProducts\Core\Checkout\CustomizedProductsCartDataCollector;
use Swag\CustomizedProducts\Migration\Migration1565933910TemplateProduct;
use Swag\CustomizedProducts\Storefront\Controller\CustomizedProductsCartController;
use Swag\CustomizedProducts\Template\TemplateEntity;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Webwirkung\VibraplastTheme\Checkout\Cart\CustomProductLineItemFactory;

#[Route(defaults: ['_routeScope' => ['store-api']])]
#[AsDecorator(decorates: ReOrderCustomizedProductsRoute::class, onInvalid: ContainerInterface::IGNORE_ON_INVALID_REFERENCE)]
class ReOrderCustomizedProductsRouteDecorator extends AbstractReOrderCustomizedProductsRoute
{

    public function __construct(
        private readonly AbstractReOrderCustomizedProductsRoute $decorated,
        private readonly CartService                            $cartService,
        private readonly EntityRepository                       $orderRepository,
        private readonly SalesChannelRepository                 $productRepository,
    )
    {
    }

    public function getDecorated(): AbstractReOrderCustomizedProductsRoute
    {
        return $this->decorated;
    }

    #[Route(path: '/store-api/customized-products/reorder/{orderId}', name: 'store-api.customized-products.reorder', methods: ['POST'])]
    public function reOrder(
        string              $orderId,
        Request             $request,
        SalesChannelContext $salesChannelContext,
        ?Cart               $cart,
    ): NoContentResponse
    {
        $cart ??= $this->cartService->getCart($salesChannelContext->getToken(), $salesChannelContext);

        if (!Uuid::isValid($orderId)) {
            throw new InvalidUuidException($orderId);
        }

        $criteria = new Criteria([$orderId]);
        $criteria->addAssociation('lineItems.product.cover.media');
        $order = $this->orderRepository->search($criteria, $salesChannelContext->getContext())->get($orderId);

        if (!$order instanceof OrderEntity) {
            throw OrderException::orderNotFound($orderId);
        }

        $orderLineItems = $order->getLineItems();
        if (!$orderLineItems instanceof OrderLineItemCollection || $orderLineItems->count() <= 0) {
            throw CartException::lineItemNotFound($orderId);
        }

        // Preserve the original order item quantities
        $referenceIdQuantityMap = [];
        foreach ($orderLineItems as $orderLineItem) {
            $referencedId = $orderLineItem->getReferencedId();
            if ($referencedId === null) {
                continue;
            }

            $referenceIdQuantityMap[$referencedId] = $orderLineItem->getQuantity();
        }

        $lineItems = LineItemTransformer::transformFlatToNested($orderLineItems);
        if ($lineItems->count() <= 0) {
            throw CartException::lineItemNotFound($orderId);
        }

        $this->sanitizeQuantities($referenceIdQuantityMap, $lineItems);

        $addedToCartCount = 0;
        foreach ($lineItems as $lineItem) {
            if (
                !\in_array(
                    $lineItem->getType(),
                    [LineItem::PRODUCT_LINE_ITEM_TYPE, CustomProductLineItemFactory::CUSTOM_PRODUCT_LINE_ITEM_TYPE, CustomizedProductsCartDataCollector::CUSTOMIZED_PRODUCTS_TEMPLATE_LINE_ITEM_TYPE],
                    true,
                )
            ) {
                continue;
            }

            // If a product is reordered check if it has a template assigned
            if ($this->productHasTemplateAssigned($lineItem, $salesChannelContext) &&
                $lineItem->getType() !== CustomProductLineItemFactory::CUSTOM_PRODUCT_LINE_ITEM_TYPE
            ) {
                $cart->addErrors(
                    new SwagCustomizedProductsTemplateAssignedError($lineItem->getReferencedId()),
                );

                continue;
            }

            $this->removeOriginalIdExtensionFromLineItem($lineItem);
            $this->cartService->add($cart, $lineItem, $salesChannelContext);
            ++$addedToCartCount;
        }

        $request->attributes->set(
            CustomizedProductsCartController::CUSTOMIZED_PRODUCTS_ADD_TO_CART_COUNT,
            $addedToCartCount,
        );

        return new NoContentResponse();
    }

    private function removeOriginalIdExtensionFromLineItem(LineItem $lineItem): void
    {
        $lineItem->removeExtension(OrderConverter::ORIGINAL_ID);

        foreach ($lineItem->getChildren() as $child) {
            $this->removeOriginalIdExtensionFromLineItem($child);
        }
    }

    /**
     * Sanitizes nested LineItem quantities back to the order quantities by referencedId recursively
     */
    private function sanitizeQuantities(array $referenceIdQuantityMap, LineItemCollection $lineItems): void
    {
        foreach ($lineItems as $lineItem) {
            $referencedId = $lineItem->getReferencedId();
            if ($referencedId === null) {
                continue;
            }

            $children = $lineItem->getChildren();
            $lineItem->setChildren(new LineItemCollection());
            $lineItem->setStackable(true);
            $lineItem->setQuantity($referenceIdQuantityMap[$referencedId]);
            $lineItem->setChildren($children);

            if ($lineItem->hasChildren()) {
                $this->sanitizeQuantities($referenceIdQuantityMap, $lineItem->getChildren());
            }
        }
    }

    private function productHasTemplateAssigned(LineItem $lineItem, SalesChannelContext $salesChannelContext): bool
    {
        $referencedId = $lineItem->getReferencedId();
        if ($referencedId === null) {
            return false;
        }

        $criteria = new Criteria([$referencedId]);
        $criteria->addAssociation(Migration1565933910TemplateProduct::PRODUCT_TEMPLATE_INHERITANCE_COLUMN);

        /** @var SalesChannelProductEntity|null $product */
        $product = $this->productRepository->search($criteria, $salesChannelContext)->first();

        // If the product is no longer available a error gets added to the cart by the ProductCartProcessor
        if ($product === null) {
            return false;
        }

        try {
            return $product->get(Migration1565933910TemplateProduct::PRODUCT_TEMPLATE_INHERITANCE_COLUMN) instanceof TemplateEntity;
        } catch (\InvalidArgumentException) {
            return false;
        }
    }
}