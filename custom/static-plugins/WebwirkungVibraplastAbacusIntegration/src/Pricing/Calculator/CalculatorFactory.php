<?php
declare(strict_types=1);

namespace Webwirkung\VibraplastAbacusIntegration\Pricing\Calculator;

use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Core\System\Unit\UnitEntity;

class CalculatorFactory implements CalculatorFactoryInterface
{
    public function __construct(
        private readonly SystemConfigService        $systemConfigService,
        private readonly PieceCalculator            $pieceCalculator,
        private readonly SquareMeterCalculator      $squareMeterCalculator,
        private readonly LineMeterCalculator        $lineMeterCalculator,
        private readonly SquareMeterPieceCalculator $squareMeterPieceCalculator,
        private readonly EntityRepository           $unitRepository,
    )
    {
    }

    public function getForProduct(ProductEntity $product, Context $context, string $salesChannelId): PricingCalculatorInterface
    {
        $productUnitId = $product->getUnitId();
        if ($productUnitId === null) {
            $this->pieceCalculator->setUnit(null);
            return $this->pieceCalculator;
        }

        $squareMeterUnitId = $this->systemConfigService->getString(
            'WebwirkungVibraplastAbacusIntegration.config.squareMeterUnitId',
            $salesChannelId
        );
        $lineMeterUnitId = $this->systemConfigService->getString(
            'WebwirkungVibraplastAbacusIntegration.config.lineMeterUnitId',
            $salesChannelId
        );
        $squareMeterPieceUnitId = $this->systemConfigService->getString(
            'WebwirkungVibraplastAbacusIntegration.config.squareMeterPieceUnitId',
            $salesChannelId
        );

        if ($squareMeterUnitId !== '' && $productUnitId === $squareMeterUnitId) {
            if (!$this->squareMeterCalculator->getUnit()) {
                $this->squareMeterCalculator->setUnit($this->fetchUnit($squareMeterUnitId, $context));
            }
            return $this->squareMeterCalculator;
        }
        if ($lineMeterUnitId !== '' && $productUnitId === $lineMeterUnitId) {
            if (!$this->lineMeterCalculator->getUnit()) {
                $this->lineMeterCalculator->setUnit($this->fetchUnit($lineMeterUnitId, $context));
            }
            return $this->lineMeterCalculator;
        }
        if ($squareMeterPieceUnitId !== '' && $productUnitId === $squareMeterPieceUnitId) {
            if (!$this->squareMeterPieceCalculator->getUnit()) {
                $this->squareMeterPieceCalculator->setUnit($this->fetchUnit($squareMeterPieceUnitId, $context));
            }
            return $this->squareMeterPieceCalculator;
        }

        $this->pieceCalculator->setUnit(null);
        return $this->pieceCalculator;
    }

    private function fetchUnit(string $unitId, Context $context): ?UnitEntity
    {
        return $this->unitRepository->search(new Criteria([$unitId]), $context)->first();
    }
}
