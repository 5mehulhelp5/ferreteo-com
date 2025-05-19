/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    'jquery',
    'jquery/ui',
    'mage/utils/strings',
    'Aitoc_ProductUnitsAndQuantities/js/constants/labels/units-labels',
    'Aitoc_ProductUnitsAndQuantities/js/validation/units/error-message'
], function ($, jQueryUi, stringsUtils, unitsLabels, errorMessage) {

    return function (target) {
        target['price-per-divider'] = {
            handler: function (value, params) {
                var allowUnits = params.allowUnits;
                var pricePer = params.pricePer;

                return !allowUnits || !stringsUtils.isEmpty(value) || !stringsUtils.isEmpty(pricePer);
            },
            message: errorMessage
        };

        return target;
    }
});
