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
 * Class UseQuantitiesStringToArrayConvector
 */
class UseQuantitiesStringToArrayConvector
{
    /**
     * @param string $str
     * @return array
     */
    public function convert($str)
    {
        $quantitiesArray = explode(',', $str);

        array_walk($quantitiesArray, function (&$value) {
            $value = (float) $value;
        });

        return $quantitiesArray;
    }
}
