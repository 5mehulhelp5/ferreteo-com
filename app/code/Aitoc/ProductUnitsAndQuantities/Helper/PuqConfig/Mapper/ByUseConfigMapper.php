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

use Aitoc\ProductUnitsAndQuantities\Api\Data\SystemPuqConfigInterface;

/**
 * Class ByUseConfigMapper
 */
class ByUseConfigMapper
{
    /**
     * @param $forGetUseConfigPuqConfig
     * @param $toCopyPuqConfig
     * @param SystemPuqConfigInterface $systemPuqConfig
     */
    public function applyByUseConfigValues(
        $forGetUseConfigPuqConfig,
        $toCopyPuqConfig,
        SystemPuqConfigInterface $systemPuqConfig
    ) {
        if ($forGetUseConfigPuqConfig->getUseConfigReplaceQty()) {
            $toCopyPuqConfig->setReplaceQty($systemPuqConfig->getReplaceQty());
        }

        if ($forGetUseConfigPuqConfig->getUseConfigIsQtyDecimal()) {
            $toCopyPuqConfig->setIsQtyDecimal($systemPuqConfig->getIsQtyDecimal());
        }

        if ($forGetUseConfigPuqConfig->getUseConfigQtyType()) {
            $toCopyPuqConfig->setQtyType($systemPuqConfig->getQtyType());
        }

        if ($forGetUseConfigPuqConfig->getUseConfigUseQuantities()) {
            $toCopyPuqConfig->setUseQuantities($systemPuqConfig->getUseQuantities());
        }

        if ($forGetUseConfigPuqConfig->getUseConfigStartQty()) {
            $toCopyPuqConfig->setStartQty($systemPuqConfig->getStartQty());
        }

        if ($forGetUseConfigPuqConfig->getUseConfigQtyIncrement()) {
            $toCopyPuqConfig->setQtyIncrement($systemPuqConfig->getQtyIncrement());
        }

        if ($forGetUseConfigPuqConfig->getUseConfigEndQty()) {
            $toCopyPuqConfig->setEndQty($systemPuqConfig->getEndQty());
        }

        if ($forGetUseConfigPuqConfig->getUseConfigAllowUnits()) {
            $toCopyPuqConfig->setAllowUnits($systemPuqConfig->getAllowUnits());
        }

        if ($forGetUseConfigPuqConfig->getUseConfigPricePer()) {
            $toCopyPuqConfig->setPricePer($systemPuqConfig->getPricePer());
        }

        if ($forGetUseConfigPuqConfig->getUseConfigPricePerDivider()) {
            $toCopyPuqConfig->setPricePerDivider($systemPuqConfig->getPricePerDivider());
        }
    }
}
