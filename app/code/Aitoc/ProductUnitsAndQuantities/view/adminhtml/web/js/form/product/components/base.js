/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    'jquery',
    'Magento_Ui/js/form/element/abstract',
    'Aitoc_ProductUnitsAndQuantities/js/form/product/product-datasource',
    'Aitoc_ProductUnitsAndQuantities/js/form/product/helper/puq-section-helper',
    "Aitoc_ProductUnitsAndQuantities/js/constants/config-keys",
    'Aitoc_ProductUnitsAndQuantities/js/constants/replace-qty'
], function (jQuery, Abstract, productDataSource, puqSectionHelper, CONFIG_KEYS, REPLACE_QTY) {
    return Abstract.extend({
        defaults: {
            valueUpdate: 'input'
        },

        getRelatedFieldKey: function () {
            return null;
        },

        onIsQtyDecimalChanged: function () {
            this.onQtyParamsChanged();
        },

        onQtyParamsChanged: function () {
            this.updateValidators(productDataSource.data.product);
            this.validate();
            this.validateRelatedFields();
        },

        updateValidators: function (datasourceProduct) {
            this.validation = this.getValidation(datasourceProduct);
        },

        getValidation: function (datasourceProduct) {
            return {};
        },

        validateRelatedFields: function () {
            var relatedFieldKey = this.getRelatedFieldKey();

            if (!relatedFieldKey) {
                return;
            }

            this.onFieldByIndex(relatedFieldKey, function (field) {
                field.updateValidators(productDataSource.data.product);
                field.validate();
            });
        },

        onFieldByIndex: function (index, callback) {
            return puqSectionHelper.onFieldByIndex(index, callback);
        },

        getUseConfigKey: function (index) {
            return 'use_config_' + index;
        }
    });
});
