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
 * Class MessagesInterface
 */
interface MessagesInterface
{
    // Should be synced with Aitoc/ProductUnitsAndQuantities/view/adminhtml/web/js/constants/messages.js
    const CONFIG_MIN_MAX_QTY_NOTICE
        = '<p>This value is overridden by the <a href="%1">«Product Units and Quantities» extension</a>.';
    const PRODUCT_MIN_MAX_QTY_NOTICE
        = 'This value is overridden by the «Product Units and Quantities» extension in «Product Units And Quantities» section.';
}
