<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Model;

use Aitoc\ProductUnitsAndQuantities\Api\Data\BaseResultProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\ResultAdminProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\ResultFrontendProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfigWithUseConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\SystemPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Helper\PuqConfig\Mapper\WithoutUseConfigMapperInterface;
use Aitoc\ProductUnitsAndQuantities\Api\PuqConfigurationInterface;
use Aitoc\ProductUnitsAndQuantities\Api\RealProductPuqConfigRepositoryInterface;
use Aitoc\ProductUnitsAndQuantities\Api\ResultFrontendProductPuqConfigRepositoryInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\PuqConfig\Adjuster\ByAspect\Qty\ByIncrementsQtyAdjuster;
use Aitoc\ProductUnitsAndQuantities\Helper\PuqConfig\Adjuster\ByProductTypeId as ByProductTypeIdPuqConfigQtyAdjuster;
use Aitoc\ProductUnitsAndQuantities\Helper\PuqConfig\Mapper\ByUseConfigMapper;
use Aitoc\ProductUnitsAndQuantities\Model\Data\ResultFrontendProductPuqConfigFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class ResultProductPuqConfigRepository
 */
class ResultFrontendProductPuqConfigRepository extends BaseResultProductPuqConfigRepository implements
    ResultFrontendProductPuqConfigRepositoryInterface
{
    private $byIncrementsQtyAdjuster;

    /**
     * ResultProductPuqConfigRepository constructor.
     *
     * @param RealProductPuqConfigRepositoryInterface $adminProductPuqConfigRepository
     * @param PuqConfigurationInterface $systemPuqConfigHelper
     * @param ResultFrontendProductPuqConfigFactory $resultProductPuqConfigFactory
     * @param ByUseConfigMapper $byUseConfigMapper
     * @param WithoutUseConfigMapperInterface $puqConfigMapper
     * @param ByIncrementsQtyAdjuster $byIncrementsQtyAdjuster
     * @param ProductRepositoryInterface $productRepository
     * @param ByProductTypeIdPuqConfigQtyAdjuster $byProductTypeIdPuqConfigQtyAdjuster
     */
    public function __construct(
        RealProductPuqConfigRepositoryInterface $adminProductPuqConfigRepository,
        PuqConfigurationInterface $systemPuqConfigHelper,
        ResultFrontendProductPuqConfigFactory $resultProductPuqConfigFactory,
        ByUseConfigMapper $byUseConfigMapper,
        WithoutUseConfigMapperInterface $puqConfigMapper,
        ByIncrementsQtyAdjuster $byIncrementsQtyAdjuster,
        ProductRepositoryInterface $productRepository,
        ByProductTypeIdPuqConfigQtyAdjuster $byProductTypeIdPuqConfigQtyAdjuster
    ) {
        parent::__construct(
            $adminProductPuqConfigRepository,
            $systemPuqConfigHelper,
            $resultProductPuqConfigFactory,
            $byUseConfigMapper,
            $puqConfigMapper,
            $productRepository,
            $byProductTypeIdPuqConfigQtyAdjuster
        );

        $this->byIncrementsQtyAdjuster = $byIncrementsQtyAdjuster;
    }

    /**
     * @param int $productId
     * @param int $storeId
     * @return BaseResultProductPuqConfigInterface|ResultFrontendProductPuqConfigInterface
     * @throws NoSuchEntityException
     */
    public function getByProductIdAndStoreId($productId, $storeId)
    {
        $puqConfig = parent::getByProductIdAndStoreId($productId, $storeId);

        return $this->adjustMinMaxByIncQty($puqConfig);
    }

    /**
     * @param BaseResultProductPuqConfigInterface $puqConfig
     * @return BaseResultProductPuqConfigInterface
     */
    private function adjustMinMaxByIncQty(BaseResultProductPuqConfigInterface $puqConfig)
    {
        $minQty = $puqConfig->getStartQty();
        $maxQty = $puqConfig->getEndQty();
        $incQty = $puqConfig->getQtyIncrement();

        $byIncrementsQtyAdjuster = $this->byIncrementsQtyAdjuster;
        $adjustedMinQty = $byIncrementsQtyAdjuster->getAdjustedMinValue($minQty, $incQty);
        $adjustedMaxQty = $byIncrementsQtyAdjuster->getAdjustedMaxValue($maxQty, $incQty);

        $puqConfig->setStartQty($adjustedMinQty);
        $puqConfig->setEndQty($adjustedMaxQty);

        return $puqConfig;
    }

    /**
     * @param PuqConfigWithUseConfigInterface $realProductPuqConfig
     * @param SystemPuqConfigInterface $systemPuqConfig
     * @return ResultAdminProductPuqConfigInterface|mixed
     */
    protected function getResultPuqConfigByRealPuqConfig(
        PuqConfigWithUseConfigInterface $realProductPuqConfig,
        SystemPuqConfigInterface $systemPuqConfig
    ) {
        $resultProductPuqConfig = $this->createReturnInterfaceNewInstance();

        $this->copyNotUseConfigProductPuqConfigValues($resultProductPuqConfig, $realProductPuqConfig);
        $this->copyUseConfigProductPuqConfigValues($realProductPuqConfig, $resultProductPuqConfig, $systemPuqConfig);

        return $resultProductPuqConfig;
    }
}
