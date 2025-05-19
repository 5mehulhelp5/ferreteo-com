/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    'jquery',
    "Aitoc_ProductUnitsAndQuantities/js/form/product/helper/fields-manager/advanced-inventory/field/base/without-use-config",
    "Aitoc_ProductUnitsAndQuantities/js/constants/catalog-inventory-keys"
], function (
    jQuery,
    WithoutUseConfigField,
    CATALOG_INVENTORY_KEYS
) {
    return WithoutUseConfigField.extend({
        defaults: {
            mainKey: CATALOG_INVENTORY_KEYS.IS_QTY_DECIMAL,
            haveContainer: false
        }
    });
});
