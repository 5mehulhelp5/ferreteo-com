/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    "Magento_Ui/js/form/element/select",
    'Aitoc_ProductUnitsAndQuantities/js/form/product/product-datasource'
], function (select, productDataSource) {
    return select.extend({
        onAllowUnitsParamsChanged: function () {
            this.updateValidators();
            this.validate();
        },

        updateValidators: function () {
            var pricePer = this.getPricePerValue();
            var pricePerDivider = this.getPricePerDividerValue();

            this.validation['allow-units'] = {
                pricePer: pricePer,
                pricePerDivider: pricePerDivider
            };
        },

        getPricePerValue: function () {
            return productDataSource.data.product.price_per;
        },

        getPricePerDividerValue: function () {
            return productDataSource.data.product.price_per_divider;
        }
    });
});
