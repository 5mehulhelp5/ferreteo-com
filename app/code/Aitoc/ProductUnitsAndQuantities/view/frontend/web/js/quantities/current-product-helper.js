/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    'Aitoc_ProductUnitsAndQuantities/js/quantities/current-product-config',
    "Aitoc_ProductUnitsAndQuantities/js/constants/config-keys"
], function (currentProductConfig, CONFIG_KEYS) {
    return {
        getLinkedProductConfig: function (productId) {
            return currentProductConfig[CONFIG_KEYS.LINKED_PRODUCTS][productId];
        },

        isConfigExists: function () {
            return (typeof currentProductConfig !== "undefined");
        }
    };
});
