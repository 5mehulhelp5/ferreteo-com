/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    'Aitoc_ProductUnitsAndQuantities/js/form/product/components/base-min-max',
    "Aitoc_ProductUnitsAndQuantities/js/constants/config-keys",
    'Aitoc_ProductUnitsAndQuantities/js/constants/replace-qty',
    "Aitoc_ProductUnitsAndQuantities/js/constants/qty-types"
], function (BaseMinMax, CONFIG_KEYS) {
    return BaseMinMax.extend({
        getRelatedFieldKey: function () {
            return CONFIG_KEYS.END_QTY;
        },

        getRelatedFieldValidatorName: function () {
            return 'less-than-equals-to';
        }
    });
});
