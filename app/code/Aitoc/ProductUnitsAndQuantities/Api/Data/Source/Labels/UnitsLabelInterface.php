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
 * Interface UnitsLabelInterface
 */
interface UnitsLabelInterface
{
    //Should be synced with label values in module's and `module-catalog-inventory` `product_form.xml`.
    // and Aitoc/ProductUnitsAndQuantities/view/base/web/js/constants/labels/allow-units-labels.js
    const ALLOW_UNITS = 'Allow Units';
    const PRICE_PER = 'Price per';
    const PRICE_PER_DIVIDER = 'Price Per Divider';
}
