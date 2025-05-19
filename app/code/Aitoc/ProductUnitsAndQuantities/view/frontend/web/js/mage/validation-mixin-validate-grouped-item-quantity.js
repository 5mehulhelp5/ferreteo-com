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

        function getPrecision(numberAsString)
        {
            var n = numberAsString.toString().split('.');
            return n.length > 1
                ? n[1].length
                : 0;
        }

        function isQtyValidByIncrement(qty, increment)
        {
            var incrementPrecision = getPrecision(increment);
            var qtyPrecision = getPrecision(qty);

            var maxPrecision = Math.max(incrementPrecision, qtyPrecision);
            var precisionFactor = Math.pow(10, maxPrecision);

            return (qty * precisionFactor) % (increment * precisionFactor) === 0;
        }

        return function (originalWidget) {
            if (!originalWidget || !puqConfig.fix_validate_grouped_qty) {
                return originalWidget;
            }

            $.validator.addMethod(VALIDATION_RULES.VALIDATE_GROUPED_ITEM_QUANTITY, function (value, element, params) {
                    //todo: common code with `validate-item-quantity` except compare to 0. Make pull-request to magento core.
                    var validator = this,
                        result,
                        // obtain values for validation
                        qty = $.mage.parseNumber(value);

                if (qty === 0) {
                    return true;
                }

                    var isMinAllowedValid = typeof params.minAllowed === 'undefined' ||
                        qty >= $.mage.parseNumber(params.minAllowed.toString()),

                        isMaxAllowedValid = typeof params.maxAllowed === 'undefined' ||
                            qty <= $.mage.parseNumber(params.maxAllowed.toString()),

                        isQtyIncrementsValid = typeof params.qtyIncrements === 'undefined' ||
                            isQtyValidByIncrement(qty, params.qtyIncrements);

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
        };
    }
);
