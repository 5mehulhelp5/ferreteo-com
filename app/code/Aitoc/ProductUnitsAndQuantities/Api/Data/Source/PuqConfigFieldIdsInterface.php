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
 * Class PuqConfigFieldIdsInterface
 */
interface PuqConfigFieldIdsInterface
{
    const REPLACE_QTY = 'replace_qty';
    const QTY_TYPE = 'qty_type';
    const USE_QUANTITIES = 'use_quantities';
    const IS_QTY_DECIMAL = 'is_qty_decimal';
    const START_QTY = 'start_qty';
    const QTY_INCREMENT = 'qty_increment';
    const END_QTY = 'end_qty';
    const ALLOW_UNITS = 'allow_units';
    const PRICE_PER = 'price_per';
    const PRICE_PER_DIVIDER = 'price_per_divider';

    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ID = 'item_id';
    const PRODUCT_ID = 'product_id';
    const STORE_ID = 'store_id';

    const USE_CONFIG_PARAMS = 'use_config_params';
}
