/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    'jquery',
    'jquery/ui',
    'mage/utils/strings',
    'Aitoc_ProductUnitsAndQuantities/js/constants/labels/units-labels',
    'Aitoc_ProductUnitsAndQuantities/js/validation/qty-steps-counter',
    'mage/translate'
], function ($, jQueryUi, stringsUtils, unitsLabels, qtyStepsCounter) {

    var ERROR_MESSAGE = 'Too many possible values for product quantities. Maximum is %1.<br/>Either increase values in field "Minimum Qty Allowed in Shopping Cart" or "Qty Increments". Alternatively, decrease value in field "Maximum Qty Allowed in Shopping Cart".';
    //should be synced with \Aitoc\ProductUnitsAndQuantities\Helper\Validator\ProductPuqConfig\StartEndIncQty\StepsCountValidator::ERROR_MESSAGE

    var stepsCount = 0;

    return function (target) {
        target['qty-max-steps-count'] = {
            handler: function (value, params) {
                stepsCount = qtyStepsCounter.getStepsCount(params['minQty'], params['maxQty'], params['incQty']);

                return stepsCount <= qtyStepsCounter.MAX_STEPS_COUNT;
            },

            message: $.mage.__(ERROR_MESSAGE).replace('%1', qtyStepsCounter.MAX_STEPS_COUNT)
        };

        return target;
    };
});
