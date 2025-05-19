/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

/**
 * @see https://github.com/magento/magento2/issues/13582#issuecomment-377970718 - minimum quantity validation message not showing
 */

define(
    [
        'jquery',
        'jquery/ui',
        'jquery/validate',
        'Aitoc_ProductUnitsAndQuantities/js/config',
        'Aitoc_ProductUnitsAndQuantities/js/quantities/constants/validation-rules'
    ],
    function ($, jQueryUi, validate, puqConfig, VALIDATION_RULES) {
        'use strict';

        return function (originalWidget) {
            if (!originalWidget || !puqConfig.fix_min_max_sale_qty_error_messages) {
                return originalWidget;
            }

            $.validator.addMethod(VALIDATION_RULES.VALIDATE_ITEM_QUANTITY, function (value, element, params) {
                    var validator = this,
                        result,
                        // obtain values for validation
                        qty = $.mage.parseNumber(value),
                        isMinAllowedValid = typeof params.minAllowed === 'undefined' ||
                            qty >= $.mage.parseNumber(params.minAllowed.toString()),
                        isMaxAllowedValid = typeof params.maxAllowed === 'undefined' ||
                            qty <= $.mage.parseNumber(params.maxAllowed.toString()),
                        isQtyIncrementsValid = typeof params.qtyIncrements === 'undefined' ||
                            isEmptyModule(qty, params.qtyIncrements);

                    result = qty > 0;

                if (result === false) {
                    validator.itemQtyErrorMessage = $.mage.__('Please enter a quantity greater than 0.');

                    return result;
                }

                    result = isMinAllowedValid;

                if (result === false) {
                    validator.itemQtyErrorMessage = $.mage.__('The fewest you may purchase is %1.')
                        .replace('%1', params.minAllowed);

                    return result;
                }

                    result = isMaxAllowedValid;

                if (result === false) {
                    validator.itemQtyErrorMessage = $.mage.__('The maximum you may purchase is %1.')
                        .replace('%1', params.maxAllowed);

                    return result;
                }

                    result = isQtyIncrementsValid;

                if (result === false) {
                    validator.itemQtyErrorMessage = $.mage
                        .__('You can buy this product only in quantities of %1 at a time.')
                        .replace('%1', params.qtyIncrements);

                    return result;
                }

                    return result;
            },
                function () {
                    return this.itemQtyErrorMessage;
                },
                false);

            return originalWidget;

            function isEmptyModule(qty, inc)
            {
                var multiplier = getMaximalMultiplier(qty, inc);

                return (qty * multiplier) % ($.mage.parseNumber(inc.toString()) * multiplier) === 0;

                function getMaximalMultiplier(number1, number2)
                {
                    var maxPrecision = getMaxPrecision(number1, number2);

                    return Math.pow(10, maxPrecision);

                    function getMaxPrecision(number1, number2)
                    {
                        var precision1 = getPrecision(number1);
                        var precision2 = getPrecision(number2);

                        return Math.max(precision1, precision2);

                        function getPrecision(number)
                        {
                            var chunks = (number + "").split(".");

                            return chunks.length > 1 ? chunks[1].length: -1;
                        }
                    }
                }
            }
        };
    }
);
