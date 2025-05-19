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
        target['price-per'] = {
            handler: function (value, params) {
                var allowUnits = params.allowUnits;
                var pricePerDivider = params.pricePerDivider;

                return !allowUnits || !stringsUtils.isEmpty(value) || !stringsUtils.isEmpty(pricePerDivider);
            },
            message: errorMessage
        };

        return target;
    }
});
