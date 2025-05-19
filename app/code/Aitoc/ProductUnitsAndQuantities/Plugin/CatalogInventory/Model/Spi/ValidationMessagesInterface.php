<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Plugin\CatalogInventory\Model\Spi;

/**
 * Class ValidationMessagesInterface
 */
class ValidationMessagesInterface
{
    //Should be synced with Aitoc/ProductUnitsAndQuantities/view/frontend/web/js/mage/messages/validate-item-quantity.js

    const NEAREST_VALUE_SINGLE = 'Use nearest allowed value: "%1".';
    const NEAREST_VALUE_MULTIPLE = 'Use nearest allowed values: "%1", "%2".';
    const NOT_ALLOWED_VALUE = 'Quantity "%1" is not in allowed values.';
}
