/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    'jquery',
    "Aitoc_ProductUnitsAndQuantities/js/form/product/helper/fields-manager/advanced-inventory/field/base/with-use-config",
    "Aitoc_ProductUnitsAndQuantities/js/constants/catalog-inventory-keys"
], function (
    jQuery,
    WithUseConfigField,
    CATALOG_INVENTORY_KEYS
) {
    return WithUseConfigField.extend({
        defaults: {
            mainKey: CATALOG_INVENTORY_KEYS.MIN_SALE_QTY,
            haveContainer: true
        }
    });
});
