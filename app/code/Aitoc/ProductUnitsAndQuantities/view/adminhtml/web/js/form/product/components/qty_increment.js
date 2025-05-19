/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    'Aitoc_ProductUnitsAndQuantities/js/form/product/components/base-min-max-qty',
    'Aitoc_ProductUnitsAndQuantities/js/form/product/product-datasource',
    'Aitoc_ProductUnitsAndQuantities/js/constants/config-keys'
], function (BaseMinMaxQty, productDataSource, CONFIG_KEYS) {

    return BaseMinMaxQty.extend({
        getValidation: function (datasourceProduct) {
            var validation = this._super();
            validation['qty-max-steps-count'] = this.getQtyStepsCountValidatorParams();

            return validation;
        },

        getQtyStepsCountValidatorParams: function () {
            var minQty = this.getPuqValueByIndex(CONFIG_KEYS.START_QTY);
            var maxQty = this.getPuqValueByIndex(CONFIG_KEYS.END_QTY);
            var incQty = this.getPuqValueByIndex(CONFIG_KEYS.QTY_INCREMENT);

            return {
                minQty: minQty,
                maxQty: maxQty,
                incQty: incQty
            };
        },

        getPuqValueByIndex: function (index) {
            return productDataSource.data.product[index];
        }
    });
});
