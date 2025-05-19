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

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\Labels\ReplaceQtyLabelInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\ReplaceQtyInterface;

/**
 * Class ReplaceQtyOptionsHelper
 */
class ReplaceQtyOptionsHelper
{
    /**
     * @return array
     */
    public function getValues()
    {
        return [
            ReplaceQtyInterface::OFF => __(ReplaceQtyLabelInterface::OFF),
            ReplaceQtyInterface::DROPDOWN => __(ReplaceQtyLabelInterface::DROPDOWN),
            ReplaceQtyInterface::SLIDER => __(ReplaceQtyLabelInterface::SLIDER),
            ReplaceQtyInterface::PLUS_MINUS => __(ReplaceQtyLabelInterface::PLUS_MINUS),
            ReplaceQtyInterface::ARROWS => __(ReplaceQtyLabelInterface::ARROWS),
            ReplaceQtyInterface::ON => __(ReplaceQtyLabelInterface::ON),
        ];
    }
}
