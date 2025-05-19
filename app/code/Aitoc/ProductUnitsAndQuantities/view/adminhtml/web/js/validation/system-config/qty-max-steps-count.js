/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */


require([
    'jquery',
    'jquery/ui',
    'jquery/validate',
    'Aitoc_ProductUnitsAndQuantities/js/validation/qty-steps-counter',
    'mage/translate'
], function ($, jqueryUi, jqValidate, qtyStepsCounter) {

    var SELECTOR_MIN_QTY = '#product_units_and_quantities_general_settings_start_qty';
    var SELECTOR_MAX_QTY = '#product_units_and_quantities_general_settings_end_qty';
    var SELECTOR_INC_QTY = '#product_units_and_quantities_general_settings_qty_increment';

    $.validator.addMethod(
        'qty-max-steps-count',
        function (value) {
            var minQty = $(SELECTOR_MIN_QTY).val();
            var maxQty = $(SELECTOR_MAX_QTY).val();
            var incQty = $(SELECTOR_INC_QTY).val();

            var stepsCount = qtyStepsCounter.getStepsCount(minQty, maxQty, incQty);

            return stepsCount < qtyStepsCounter.MAX_STEPS_COUNT;
        },
        function () {
            return $.mage.__(qtyStepsCounter.ERROR_MESSAGE).replace('%1', qtyStepsCounter.MAX_STEPS_COUNT);
        }
    );
});
