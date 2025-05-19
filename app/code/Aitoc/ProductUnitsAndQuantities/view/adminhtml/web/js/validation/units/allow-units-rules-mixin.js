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
        target['allow-units'] = {
            handler: function (value, params) {
                var pricePer = params.pricePer;
                var pricePerDivider = params.pricePerDivider;

                return !value || !stringsUtils.isEmpty(pricePer) || !stringsUtils.isEmpty(pricePerDivider);
            },
            message: errorMessage
        };

        return target;
    }
});
