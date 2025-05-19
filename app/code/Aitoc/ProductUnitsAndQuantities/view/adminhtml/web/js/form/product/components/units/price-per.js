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
                var pricePerDivider = this.getPricePerDividerValue();

                this.validation['price-per'] = {
                    allowUnits: allowUnits,
                    pricePerDivider: pricePerDivider
                };
            },

            getAllowUnitsValue: function () {
                return productDataSource.data.product.allow_units;
            },

            getPricePerDividerValue: function () {
                return productDataSource.data.product.price_per_divider;
            },
        });
    }
);
