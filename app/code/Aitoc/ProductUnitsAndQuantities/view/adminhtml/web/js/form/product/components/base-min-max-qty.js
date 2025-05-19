/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    'Aitoc_ProductUnitsAndQuantities/js/form/product/components/base',
    "Aitoc_ProductUnitsAndQuantities/js/constants/config-keys",
    "Aitoc_ProductUnitsAndQuantities/js/constants/qty-types"
], function (Base, CONFIG_KEYS, QTY_TYPES) {
    return Base.extend({
        getValidation: function (datasourceProduct) {
            var validation = this._super();
            validation['validate-no-empty'] = true;
            validation['validate-number'] = true;
            validation['validate-integer'] = !datasourceProduct.is_qty_decimal;

            return validation;
        },

        disableValidatorRequired: function (datasourceProduct) {
            if (this._super(datasourceProduct)) {
                return true;
            }

            return datasourceProduct[CONFIG_KEYS.QTY_TYPE] !== QTY_TYPES.DYNAMIC;
        }
    });
});
