/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define(
    ['jquery', 'Aitoc_ProductUnitsAndQuantities/js/config', 'jquery/ui'],
    function ($, puqConfig) {
        'use strict';

        return function (originalWidget) {
            if (!originalWidget || !puqConfig.fix_validate_grouped_qty) {
                return originalWidget;
            }
            // @see https://github.com/magento/magento2/pull/14752/files
            //todo: add current version validation
            //fix to allow float less then 1 in grouped product items
            $.validator.addMethod('validate-grouped-qty', function (value, element, params) {
                    var result = false,
                        total = 0;

                    $(params).find('input[data-validate*="validate-grouped-qty"]').each(function (i, e) {
                        var val = $(e).val(),
                            valInt;

                        if (val && val.length > 0) {
                            result = true;
                            valInt = parseFloat(val) || 0;

                            if (valInt >= 0) {
                                total += valInt;
                            } else {
                                result = false;

                                return result;
                            }
                        }
                    });

                    return result && total > 0;
            },
                $.mage.__('Please specify the quantity of product(s).'),
                false);

            return originalWidget;
        };
    }
);
