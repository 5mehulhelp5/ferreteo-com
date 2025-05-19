/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */


define([
    'jquery',
    'jquery/ui',
    'mage/utils/strings',
    'Aitoc_ProductUnitsAndQuantities/js/constants/labels/units-labels',
    'mage/translate'
], function ($, jQueryUi, stringsUtils, unitsLabels) {

    return function (target) {
        target['comma-separated-floats'] = {
            handler: function (value) {
                var chunks = value.split(',');

                var hasError = false;

                $.each(chunks, function (key, value) {
                    var trimmedValue = value.trim();
                    if (parseFloat(trimmedValue) != trimmedValue) {
                        hasError = true;

                        return false;
                    }
                });

                return !hasError;
            },
            message: $.mage.__('Not valid comma-separated floats string.')
                .replace('%1', $.mage.__(unitsLabels.PRICE_PER))
                .replace('%2', $.mage.__(unitsLabels.PRICE_PER_DIVIDER))
                .replace('%3', $.mage.__(unitsLabels.ALLOW_UNITS))
        };

        return target;
    }
});
