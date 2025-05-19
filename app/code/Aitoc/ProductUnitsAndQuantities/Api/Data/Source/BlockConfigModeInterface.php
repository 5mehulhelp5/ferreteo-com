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
 * Interface BlockConfigModeInterface
 */
interface BlockConfigModeInterface
{
    /* Frontend */

    /* Frontend > product page */
    const GROUPED_VIEW = 'grouped_view'; //type = 'grouped'
    const BUNDLE = 'bundle'; //type = 'bundle'
    const CONFIGURABLE = 'configurable';
    const PRODUCT = 'product'; //all other

    /* Frontend > other pages */
    const CART = 'cart';
    const WISHLIST = 'wishlist';

    const GRID = 'grid';

    /* Admin */
    /* Admin > Sales > Orders > Order */
    const ORDER_ADMIN = 'order_admin';
}
