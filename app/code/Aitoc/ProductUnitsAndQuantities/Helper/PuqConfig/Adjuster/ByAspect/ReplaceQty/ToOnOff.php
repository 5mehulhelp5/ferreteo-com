<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Helper\PuqConfig\Adjuster\ByAspect\ReplaceQty;

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\ReplaceQtyInterface;

/**
 * Class ToOnOff
 */
class ToOnOff
{
    /**
     * @param int $replaceQty
     * @return int
     */
    public function convert($replaceQty)
    {
        return in_array($replaceQty, [ReplaceQtyInterface::OFF, ReplaceQtyInterface::ON])
            ? $replaceQty
            : ReplaceQtyInterface::ON;
    }
}
