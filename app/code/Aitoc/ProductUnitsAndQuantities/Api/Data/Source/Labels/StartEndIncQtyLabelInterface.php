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
 * Interface StartEndIncQtyLabelInterface
 */
interface StartEndIncQtyLabelInterface
{
    //Should be synced with label values in module's and `module-catalog-inventory` `product_form.xml`.
    const START_QTY = 'Minimum Qty Allowed in Shopping Cart'; //min_sale_qty
    const QTY_INCREMENT = 'Qty Increments'; //qty_increments
    const END_QTY = 'Maximum Qty Allowed in Shopping Cart'; //max_sale_qty
}
