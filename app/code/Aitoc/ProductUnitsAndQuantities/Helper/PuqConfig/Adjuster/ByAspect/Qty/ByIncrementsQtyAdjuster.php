<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Helper\PuqConfig\Adjuster\ByAspect\Qty;

/**
 * Class ByIncrementsQtyAdjuster
 */
class ByIncrementsQtyAdjuster
{
    /**
     * @param float $minValue
     * @param float $increment
     * @return float
     */
    public function getAdjustedMinValue($minValue, $increment)
    {
        if (!$increment) {
            return $minValue;
        }

        $factor = ceil($minValue / $increment);

        return $increment * $factor;
    }

    /**
     * @param float $maxValue
     * @param float $increment
     * @return float
     */
    public function getAdjustedMaxValue($maxValue, $increment)
    {
        if (!$increment) {
            return $maxValue;
        }

        $factor = floor($maxValue / $increment);

        return $increment * $factor;
    }
}
