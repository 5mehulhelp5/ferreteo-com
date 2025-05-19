/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    'jquery',
    'Aitoc_ProductUnitsAndQuantities/js/form/product/components/base',
    'Aitoc_ProductUnitsAndQuantities/js/form/product/helper/puq-section-helper',
    'Aitoc_ProductUnitsAndQuantities/js/form/product/product-datasource',
    "Aitoc_ProductUnitsAndQuantities/js/constants/config-keys",
    "Aitoc_ProductUnitsAndQuantities/js/constants/qty-types"
], function (jQuery ,Base, puqSectionHelper, productDatasource, CONFIG_KEYS, QTY_TYPES) {
    return Base.extend({
        defaults: {
            valueUpdate: 'input'
        },

        getValidation: function (datasourceProduct) {
            var validation = this._super();
            var validatorName = this.getValidatorName(datasourceProduct[CONFIG_KEYS.IS_QTY_DECIMAL]);
            validation[validatorName] = true;

            return validation;
        },

        getValidatorName: function (isQtyDecimal) {
            return isQtyDecimal
                ? 'comma-separated-floats'
                : 'comma-separated-integers';
        },

        disableValidatorRequired: function (datasourceProduct) {
            if (this._super(datasourceProduct)) {
                return true;
            }

            return datasourceProduct[CONFIG_KEYS.QTY_TYPE] === QTY_TYPES.DYNAMIC;
        }
    });
});
