<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Helper\PuqConfig\Mapper;

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfig\PuqConfigBaseSettersInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfig\PuqConfigWithUseConfigGettersInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Helper\PuqConfig\Mapper\WithUseConfigMapperInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\RealProductPuqConfigInterface;

/**
 * Class WithPuqConfigMapper
 */
class WithUseConfigPuqConfigMapper extends WithoutUseConfigPuqConfigMapper implements WithUseConfigMapperInterface
{
    /**
     * @param RealProductPuqConfigInterface $from
     * @param PuqConfigBaseSettersInterface $to
     */
    public function mapNotNullUseConfigValues(
        $from,
        PuqConfigBaseSettersInterface $to
    ) {
        /* QTY  */
        if (($useConfigReplaceQty = $from->getUseConfigReplaceQty()) !== null) {
            $to->setUseConfigReplaceQty($useConfigReplaceQty);
        }

        if (($useConfigIsQtyDecimal = $from->getUseConfigIsQtyDecimal()) !== null) {
            $to->setUseConfigIsQtyDecimal($useConfigIsQtyDecimal);
        }

        if (($useConfigQtyType = $from->getUseConfigQtyType()) !== null) {
            $to->setUseConfigQtyType($useConfigQtyType);
        }

        if (($useConfigUseQuantities = $from->getUseConfigUseQuantities()) !== null) {
            $to->setUseConfigUseQuantities($useConfigUseQuantities);
        }

        if (($useConfigStartQty = $from->getUseConfigStartQty()) !== null) {
            $to->setUseConfigStartQty($useConfigStartQty);
        }

        if (($useConfigQtyIncrement = $from->getUseConfigQtyIncrement()) !== null) {
            $to->setUseConfigQtyIncrement($useConfigQtyIncrement);
        }

        if (($useConfigEndQty = $from->getUseConfigEndQty()) !== null) {
            $to->setUseConfigEndQty($useConfigEndQty);
        }

        /* UNITS */
        if (($useConfigAllowUnits = $from->getUseConfigAllowUnits()) !== null) {
            $to->setUseConfigAllowUnits($useConfigAllowUnits);
        }

        if (($useConfigPricePerDivider = $from->getUseConfigPricePerDivider()) !== null) {
            $to->setUseConfigPricePerDivider($useConfigPricePerDivider);
        }

        if (($useConfigPricePer = $from->getUseConfigPricePer()) !== null) {
            $to->setUseConfigPricePer($useConfigPricePer);
        }
    }
}
