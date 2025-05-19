/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    'Aitoc_ProductUnitsAndQuantities/js/form/product/components/base-min-max',
    "Aitoc_ProductUnitsAndQuantities/js/constants/config-keys"
], function (BaseMinMax, CONFIG_KEYS) {
    return BaseMinMax.extend({
        getRelatedFieldKey: function () {
            return CONFIG_KEYS.START_QTY;
        },

        getRelatedFieldValidatorName: function () {
            return 'greater-than-equals-to';
        }
    });
});
