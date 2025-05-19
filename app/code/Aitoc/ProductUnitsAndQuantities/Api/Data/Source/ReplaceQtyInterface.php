<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Api\Data\Source;

/**
 * Interface ReplaceQtyInterface
 */
interface ReplaceQtyInterface
{
    // Should be synced with Aitoc/ProductUnitsAndQuantities/view/base/web/js/constants/replace-qty.js
    const OFF = 0;

    const DROPDOWN = 1;
    const SLIDER = 2;
    const PLUS_MINUS = 3;
    const ARROWS = 4;

    const ON = 100; //For grouped/configurable product
}
