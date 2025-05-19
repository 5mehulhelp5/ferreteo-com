<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Api\Data\Source\Labels;

/**
 * Interface ReplaceQtyInterface
 */
interface ReplaceQtyLabelInterface
{
    const OFF = 'Off';
    const DROPDOWN = 'Dropdown';
    const SLIDER = 'Slider';
    const PLUS_MINUS = 'Plus Minus';
    const ARROWS = 'Arrows';

    const ON = 'On'; // For Grouped and Configurable products
}
