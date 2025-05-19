<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Api\Setup;

/**
 * Class SetupConstantsInterface
 */
interface SetupConstantsInterface
{
    const PRODUCT_PUQ_CONFIG_TABLE_NAME = 'aitoc_product_units_and_quantities';
    const ORDER_ITEM_PUQ_CONFIG_TABLE_NAME = 'aitoc_product_units_and_quantities_orders';

    const FIELD_ITEM_ID = 'item_id';
    const FIELD_PRODUCT_ID = 'product_id';

    const FIELD_REPLACE_QTY = 'replace_qty';
    const FIELD_IS_QTY_DECIMAL = 'is_qty_decimal';
    const FIELD_QTY_TYPE = 'qty_type';
    const FIELD_USE_QUANTITIES = 'use_quantities';
    const FIELD_START_QTY = 'start_qty';
    const FIELD_QTY_INCREMENT = 'qty_increment';
    const FIELD_STORE_ID = 'store_id';
    const FIELD_END_QTY = 'end_qty';
    const FIELD_ALLOW_UNITS = 'allow_units';
    const FIELD_PRICE_PER = 'price_per';
    const FIELD_PRICE_PER_DIVIDER = 'price_per_divider';

    const FIELD_ORDER_ITEM_ID = 'order_item_id';
    const FIELD_USE_CONFIG_PARAMS = 'use_config_params';

    const USE_CONFIG_PARAMS_FIELD_NAME = 'use_config_params';

    const USE_CONFIG_PREFIX = 'use_config_';
}
