/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define(
    [
        'Aitoc_ProductUnitsAndQuantities/js/form/product/components/units/allow-units-param-text',
        'Aitoc_ProductUnitsAndQuantities/js/form/product/product-datasource'
    ],
    function (allowUnitsParamText, productDataSource) {
        return allowUnitsParamText.extend({
            updateValidators: function () {
                var allowUnits = this.getAllowUnitsValue();
                var pricePer = this.getPricePerValue();

                this.validation['price-per-divider'] = {
                    allowUnits: allowUnits,
                    pricePer: pricePer
                };
            },

            getAllowUnitsValue: function () {
                return productDataSource.data.product.allow_units;
            },

            getPricePerValue: function () {
                return productDataSource.data.product.price_per;
            },
        });
    }
);
