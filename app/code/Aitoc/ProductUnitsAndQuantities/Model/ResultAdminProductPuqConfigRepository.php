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

use Aitoc\ProductUnitsAndQuantities\Api\Data\ResultAdminProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfig\PuqConfigUseConfigSettersInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfigWithUseConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\SystemPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Helper\PuqConfig\Mapper\WithUseConfigMapperInterface;
use Aitoc\ProductUnitsAndQuantities\Api\PuqConfigurationInterface;
use Aitoc\ProductUnitsAndQuantities\Api\RealProductPuqConfigRepositoryInterface;
use Aitoc\ProductUnitsAndQuantities\Api\ResultAdminProductPuqConfigRepositoryInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\PuqConfig\Adjuster\ByProductTypeId as ByProductTypeIdPuqConfigQtyAdjuster;
use Aitoc\ProductUnitsAndQuantities\Helper\PuqConfig\Mapper\ByUseConfigMapper;
use Aitoc\ProductUnitsAndQuantities\Model\Data\ResultAdminProductPuqConfigFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;

/**
 * Class ResultAdminProductPuqConfigRepository
 */
class ResultAdminProductPuqConfigRepository extends BaseResultProductPuqConfigRepository implements
    ResultAdminProductPuqConfigRepositoryInterface
{
    /**
     * ResultProductPuqConfigRepository constructor.
     * @param RealProductPuqConfigRepositoryInterface $adminProductPuqConfigRepository
     * @param PuqConfigurationInterface $systemPuqConfigHelper
     * @param ResultAdminProductPuqConfigFactory $resultProductPuqConfigFactory
     * @param ByUseConfigMapper $byUseConfigMapper
     * @param WithUseConfigMapperInterface $puqConfigMapper
     * @param ProductRepositoryInterface $productRepository
     * @param ByProductTypeIdPuqConfigQtyAdjuster $byProductTypeIdPuqConfigQtyAdjuster
     */
    // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
    public function __construct(
        RealProductPuqConfigRepositoryInterface $adminProductPuqConfigRepository,
        PuqConfigurationInterface $systemPuqConfigHelper,
        ResultAdminProductPuqConfigFactory $resultProductPuqConfigFactory,
        ByUseConfigMapper $byUseConfigMapper,
        WithUseConfigMapperInterface $puqConfigMapper,
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

        $this->copyUseConfigFieldValues($resultProductPuqConfig, $realProductPuqConfig);
        $this->copyNotUseConfigProductPuqConfigValues($resultProductPuqConfig, $realProductPuqConfig);
        $this->copyUseConfigProductPuqConfigValues($realProductPuqConfig, $resultProductPuqConfig, $systemPuqConfig);

        return $resultProductPuqConfig;
    }

    /**
     * @param $resultProductPuqConfig
     * @param $sourceProductPuqConfig
     */
    protected function copyUseConfigFieldValues(
        $resultProductPuqConfig,
        $sourceProductPuqConfig
    ) {
        $this->puqConfigMapper->mapNotNullUseConfigValues($sourceProductPuqConfig, $resultProductPuqConfig);
    }

    /**
     * @param SystemPuqConfigInterface $systemPuqConfig
     * @return ResultAdminProductPuqConfigInterface
     */
    protected function getSystemProductPuqConfigValues(SystemPuqConfigInterface $systemPuqConfig)
    {
        $ret = parent::getSystemProductPuqConfigValues($systemPuqConfig);
        $this->setUseConfigFieldToTrue($ret);

        return $ret;
    }

    /**
     * @param PuqConfigUseConfigSettersInterface $ret
     */
    private function setUseConfigFieldToTrue(PuqConfigUseConfigSettersInterface $ret)
    {
        $this->setUseConfigFieldValue($ret, true);
    }

    /**
     * @param PuqConfigUseConfigSettersInterface $puqConfig
     * @param bool $value
     */
    private function setUseConfigFieldValue(PuqConfigUseConfigSettersInterface $puqConfig, $value)
    {
        $puqConfig
            ->setUseConfigReplaceQty($value)
            ->setUseConfigIsQtyDecimal($value)
            ->setUseConfigQtyType($value)
            ->setUseConfigUseQuantities($value)
            ->setUseConfigStartQty($value)
            ->setUseConfigQtyIncrement($value)
            ->setUseConfigEndQty($value)
            ->setUseConfigAllowUnits($value)
            ->setUseConfigPricePer($value)
            ->setUseConfigPricePerDivider($value);
    }
}
