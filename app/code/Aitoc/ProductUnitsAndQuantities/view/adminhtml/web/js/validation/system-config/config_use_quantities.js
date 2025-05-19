/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

require([
    'jquery',
    'jquery/ui',
    'jquery/validate',
    'mage/translate'
], function ($) {

    var isQtyDecimalSelector = '#product_units_and_quantities_general_settings_is_qty_decimal';

    $.validator.addMethod(
        'validate-use-quantities',
        function (value) {
            var validateResult = true;
            var lastStr = null;

            var isQtyDecimal = getIsQtyDecimal();
            var values = value.split(',');
            $.each(values, function (index, str) {
                var number = Number(str);
                validateResult = isQtyDecimal ? isNumber(number) : isInt(number);

                if (!validateResult) {
                    lastStr = str;

                    return false;
                }
            });

            this.lastStr = lastStr;
            this.isQtyDecial = isQtyDecimal;

            return validateResult;
        },
        function () {
            var expectedType = this.isQtyDecial ? 'fractional number' : 'whole number';

            return $.mage.__('Invalid value: "{0}". Enter {1} expected.')
                .replace('{0}', this.lastStr)
                .replace('{1}', expectedType);
        }
    );


    function isInt(n)
    {
        return Number.isInteger(n);
    }

    function isNumber(n)
    {
        return Number(n) === n;
    }

    function getIsQtyDecimal()
    {
        return $(isQtyDecimalSelector).val() === '1';
    }
});
