<?php declare(strict_types=1);

namespace Webwirkung\VibraplastAbacusIntegration\Decorator;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartBehavior;
use Shopware\Core\Checkout\Cart\CartDataCollectorInterface;
use Shopware\Core\Checkout\Cart\CartProcessorInterface;
use Shopware\Core\Checkout\Cart\LineItem\CartDataCollection;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\LineItem\LineItemCollection;
use Shopware\Core\Checkout\Cart\Price\CashRounding;
use Shopware\Core\Checkout\Cart\Price\QuantityPriceCalculator;
use Shopware\Core\Checkout\Cart\Price\Struct\QuantityPriceDefinition;
use Shopware\Core\Checkout\Cart\Price\Struct\ReferencePrice;
use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Struct\ArrayStruct;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\Unit\UnitEntity;
use Webwirkung\VibraplastAbacusIntegration\Pricing\Calculator\CalculatorFactoryInterface;
use WebwirkungAbacusIntegration\Api\Endpoints\Payload\FindProductsPricesPayload;
use WebwirkungAbacusIntegration\Api\Endpoints\Payload\ProductPricePosition;
use WebwirkungAbacusIntegration\Exception\AbacusApiException;
use WebwirkungAbacusIntegration\Plugin\ConfigFactoryInterface;
use WebwirkungAbacusIntegration\Service\Abacus\Price;
use WebwirkungAbacusIntegration\System\CustomerGroup;

class OverwriteCustomerCartPricesDecorator implements CartDataCollectorInterface, CartProcessorInterface
{
    private array $productCache = [];

    public function __construct(
        private QuantityPriceCalculator    $calculator,
        private Price                      $price,
        private ConfigFactoryInterface     $configFactory,
        private CustomerGroup              $customerGroup,
        private CalculatorFactoryInterface $calculatorFactory,
        private EntityRepository           $productRepository,
        private CashRounding               $rounding,
    )
    {
    }

    public function collect(
        CartDataCollection  $data,
        Cart                $original,
        SalesChannelContext $context,
        CartBehavior        $behavior
    ): void
    {
        $salesChannelId = $context->getSalesChannelId();
        $config = $this->configFactory->create($salesChannelId);

        if ($config->isPricingApiEnabled() === false) {
            return;
        }

        $customer = $context->getCustomer();
        $customerNumber = 0;
        $customerGroupId = $context->getCurrentCustomerGroup()->getId();

        if ($customer instanceof CustomerEntity) {
            $isGuest = $customer->getGuest();
            $customerNumber = $isGuest ? 0 : (int)($customer->getCustomFields()['abacus_debtor_number'] ?? 0);
            $customerGroupId = $customer->getGroupId();
        }

        if (
            $customer === null
            && $config->isPricingApiForNotLoggedInEnabled() === false
        ) {
            return;
        }

        $products = $original->getLineItems()->filterType(LineItem::PRODUCT_LINE_ITEM_TYPE);
        $products = $this->filterAlreadyFetchedPrices($products, $data);

        if ($products->count() === 0) {
            return;
        }

        $currency = $context->getCurrency();

        $productPricePositions = array_reduce(
            $products->getElements(),
            function (array $acc, LineItem $product) use ($context) {
                $payload = $product->getPayload();
                $customFields = $payload['customFields'];
                $abacusProductId = (int)($customFields['abacus_product_id'] ?? 0);

                if ($abacusProductId === 0) {
                    return $acc;
                }

                $productEntity = $this->getProductForLineItem($product, $context->getContext());
                if (!$productEntity) {
                    return $acc;
                }

                $calculator = $this->calculatorFactory->getForProduct($productEntity, $context->getContext(), $context->getSalesChannelId());
                $quantity = $product->getQuantity() * $calculator->calculateQuantityForProduct($productEntity);

                $acc[] = new ProductPricePosition(
                    (int)$customFields['abacus_product_id'],
                    (int)($customFields['abacus_product_variant_id'] ?? 0),
                    $quantity,
                    $product->getPayload()['productNumber']
                );

                return $acc;
            },
            []
        );

        if ($productPricePositions === []) {
            return;
        }

        try {
            $prices = $this->price->getCartProductsPrices(
                $context->getSalesChannelId(),
                new FindProductsPricesPayload(
                    $customerNumber,
                    $currency->getIsoCode(),
                    array_values($productPricePositions),
                )
            );
        } catch (AbacusApiException) {
            return;
        }

        $displayGross = $this->customerGroup->getDisplayGross($customerGroupId);
        $priceType = $displayGross ? 'PriceInclTax' : 'PriceExclTax';

        foreach ($products as $product) {
            $key = $this->buildKey($product->getId());
            $payload = $product->getPayload();
            $customFields = $payload['customFields'];
            if (!isset($customFields['abacus_product_id'])) {
                continue;
            }
            $abacusProductId = (int)$customFields['abacus_product_id'];
            $newUnitPrice = $prices[$abacusProductId]['PerUnitValue'][$priceType] ?? null;

            if ($newUnitPrice === null) {
                continue;
            }

            $productEntity = $this->getProductForLineItem($product, $context->getContext());

            if (!$productEntity) {
                return;
            }

            $adjustedPrice = $newUnitPrice;
            $calculator = $this->calculatorFactory->getForProduct($productEntity, $context->getContext(), $context->getSalesChannelId());
            $adjusted = $calculator->convertToPiecePriceForProduct($newUnitPrice, $productEntity);
            $itemQuantity = $calculator->calculateQuantityForProduct($productEntity);
            if ($adjusted !== null) {
                $adjustedPrice = $adjusted;
            }

            $data->set($key, new ArrayStruct([
                'newUnitPrice' => $newUnitPrice,
                'newPiecePrice' => $adjustedPrice,
                'itemQuantity' => $itemQuantity,
                'unit' => $calculator->getUnit(),
            ]));
        }
    }

    public function process(
        CartDataCollection  $data,
        Cart                $original,
        Cart                $toCalculate,
        SalesChannelContext $context,
        CartBehavior        $behavior
    ): void
    {
        $products = $toCalculate->getLineItems()->filterType(LineItem::PRODUCT_LINE_ITEM_TYPE);

        foreach ($products as $product) {
            $key = $this->buildKey($product->getReferencedId());

            if (!$data->has($key) || $data->get($key) === null) {
                continue;
            }

            $newPrice = $data->get($key);

            $newPiecePrice = $this->rounding->cashRound( $newPrice['newPiecePrice'], $context->getItemRounding());
            $newUnitPrice = $this->rounding->cashRound($newPrice['newUnitPrice'], $context->getItemRounding());

            $definition = new QuantityPriceDefinition(
                $newPiecePrice,
                $product->getPrice()->getTaxRules(),
                $product->getPrice()->getQuantity()
            );

            $calculated = $this->calculator->calculate($definition, $context);
            $unit = $newPrice['unit'];
            if ($unit instanceof UnitEntity) {
                $calculated->assign([
                    'referencePrice' => new ReferencePrice(
                        $newUnitPrice,
                        $newUnitPrice,
                        1,
                        $unit->getShortCode()
                    ),
                ]);
            }

            $product->setPrice($calculated);
            $product->setPriceDefinition($definition);
        }
    }


    private function filterAlreadyFetchedPrices(LineItemCollection $products, CartDataCollection $data): LineItemCollection
    {
        return $products->filter(
            fn(LineItem $product) => $data->has($this->buildKey($product->getId())) === false
        );
    }

    private function buildKey(string $id): string
    {
        return sprintf('abacus-price-overwrite-%s', $id);
    }

    private function getProductForLineItem(LineItem $lineItem, Context $context): ?ProductEntity
    {
        $productId = $lineItem->getReferencedId();
        if (!$productId) {
            return null;
        }

        return $this->getProduct($productId, $context);
    }

    private function getProduct(string $id, Context $context): ?ProductEntity
    {
        $ctxHash = spl_object_hash($context);
        if (isset($this->productCache[$ctxHash][$id])) {
            return $this->productCache[$ctxHash][$id];
        }

        $criteria = new Criteria([$id]);
        /** @var ProductEntity|null $product */
        $product = $this->productRepository->search($criteria, $context)->first();

        if (!isset($this->productCache[$ctxHash])) {
            $this->productCache[$ctxHash] = [];
        }
        $this->productCache[$ctxHash][$id] = $product;

        return $product;
    }
}