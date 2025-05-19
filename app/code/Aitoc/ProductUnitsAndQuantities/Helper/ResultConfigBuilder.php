<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Helper;

use Aitoc\ProductUnitsAndQuantities\Api\Data\RealProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\SystemPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\ProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Helper\PuqConfig\Mapper\WithUseConfigMapperInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\PuqConfig\Mapper\ByUseConfigMapper;

class ResultConfigBuilder
{
    public function __construct(
        WithUseConfigMapperInterface $withUseConfigMapper,
        ByUseConfigMapper $byUseConfigMapper
    ) {
        $this->withUseConfigMapper = $withUseConfigMapper;
        $this->byUseConfigMapper = $byUseConfigMapper;
    }

    /**
     * @var WithUseConfigMapperInterface
     */
    private $withUseConfigMapper;

    /**
     * @var ByUseConfigMapper
     */
    private $byUseConfigMapper;

    /**
     * @param RealProductPuqConfigInterface $sourceRealPuqConfig
     * @param RealProductPuqConfigInterface $inputPuqConfig
     * @param SystemPuqConfigInterface $systemPuqConfig
     * @return ProductPuqConfigInterface
     */
    public function getResultPuqConfig(
        RealProductPuqConfigInterface $sourceRealPuqConfig,
        RealProductPuqConfigInterface $inputPuqConfig,
        SystemPuqConfigInterface $systemPuqConfig
    ) {
        $result = clone $sourceRealPuqConfig;
        $result
            ->setProductId($inputPuqConfig->getProductId())
            ->setStoreId($inputPuqConfig->getStoreId())
        ;


        $this->withUseConfigMapper->mapNotNullValues($inputPuqConfig, $result);
        $this->withUseConfigMapper->mapNotNullUseConfigValues($inputPuqConfig, $result);
        $this->withUseConfigMapper->fillNullValues($systemPuqConfig, $result);
        $this->fillTrueNullUseConfigValues($result);

        $this->byUseConfigMapper->applyByUseConfigValues(
            $result,
            $result,
            $systemPuqConfig
        );

        return $result;
    }

    /**
     * @param $from
     * @param $to
     */
    private function fillTrueNullUseConfigValues($to)
    {
        /** QTY */
        if ($to->getUseConfigReplaceQty() === null) {
            $to->setUseConfigReplaceQty(true);
        }

        if (($to->getUseConfigIsQtyDecimal()) === null) {
            $to->setUseConfigIsQtyDecimal(true);
        }

        if (($to->getUseConfigQtyType()) === null) {
            $to->setUseConfigQtyType(true);
        }

        if (($to->getUseConfigUseQuantities()) === null) {
            $to->setUseConfigUseQuantities(true);
        }

        if (($to->getUseConfigUseQuantities()) === null) {
            $to->setUseConfigUseQuantities(true);
        }

        if (($to->getUseConfigStartQty()) === null) {
            $to->setUseConfigStartQty(true);
        }

        if (($to->getUseConfigQtyIncrement()) === null) {
            $to->setUseConfigQtyIncrement(true);
        }

        if (($to->getUseConfigEndQty()) === null) {
            $to->setUseConfigEndQty(true);
        }

        /** UNITS */
        if (($to->getUseConfigAllowUnits()) === null) {
            $to->setUseConfigAllowUnits(true);
        }

        if (($to->getUseConfigPricePerDivider()) === null) {
            $to->setUseConfigPricePerDivider(true);
        }

        if (($to->getUseConfigPricePer()) === null) {
            $to->setUseConfigPricePer(true);
        }
    }

}
