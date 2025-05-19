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

/**
 * Class UseQuantitiesHelper
 */
class UseQuantitiesHelper
{
    // should be synced with Aitoc/ProductUnitsAndQuantities/view/frontend/web/js/quantities/controls/helper.js

    /**
     * @param float $value
     * @param float[] $values
     * @return float
     */
    public function getNearestValue($value, $values)
    {
        $minValue = $values[0];
        $nearest = $minValue;

        $diff = $this->absDiff($minValue, $value);

        foreach ($values as $itemValue) {
            $newDiff = $this->absDiff($itemValue, $value);

            if ($newDiff > $diff) {
                return $nearest;
            } elseif ($newDiff < $diff) {
                $diff = $newDiff;
                $nearest = $itemValue;
            }
        }

        return $nearest;
    }

    /**
     * @param float $a
     * @param float $b
     * @return float
     */
    private function absDiff($a, $b)
    {
        return abs($a - $b);
    }

    /**
     * @param float $value
     * @param float[] $values
     * @return float
     */
    public function getDownValue($value, $values)
    {
        $minValue = $values[0];

        if ($value <= $minValue) {
            return $minValue;
        }

        $nearestValue = $this->getNearestValue($value, $values);

        if ($nearestValue < $value) {
            return $nearestValue;
        }

        $nearestValueIndex = array_search($nearestValue, $values);

        if ($nearestValueIndex === 0) {
            return $nearestValue;
        }

        return $values[$nearestValueIndex - 1];
    }

    /**
     * @param float $value
     * @param float[] $values
     * @return float
     */
    public function getUpValue($value, $values)
    {
        $maxValueIndex = count($values) - 1;
        $maxValue = $values[$maxValueIndex];

        if ($value >= $maxValue) {
            return $maxValue;
        }

        $nearestValue = $this->getNearestValue($value, $values);

        if ($nearestValue > $value) {
            return $nearestValue;
        }

        $nearestValueIndex = array_search($nearestValue, $values);

        if ($nearestValueIndex === $maxValueIndex) {
            return $nearestValue;
        }

        return $values[$nearestValueIndex + 1];
    }
}
