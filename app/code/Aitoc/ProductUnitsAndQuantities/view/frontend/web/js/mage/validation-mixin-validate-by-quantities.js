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
        "Aitoc_ProductUnitsAndQuantities/js/quantities/controls/helper",
        'Aitoc_ProductUnitsAndQuantities/js/quantities/constants/validation-rules',
        'Aitoc_ProductUnitsAndQuantities/js/mage/messages/validate-item-quantity'
    ],
    function ($, jQueryUi, validate, puqConfig, controlHelper, VALIDATION_RULES, validationMessages) {
        'use strict';

        return function (originalWidget) {
            if (!originalWidget) {
                return originalWidget;
            }
            $.validator.addMethod(VALIDATION_RULES.VALIDATE_BY_QUANTITIES, function (value, element, possibleValues) {
                var validator = this,
                    result,
                    // obtain values for validation
                    qty = $.mage.parseNumber(value);

                if ($.inArray(qty, possibleValues) !== -1) {
                    return true;
                }

                    validator.itemQtyErrorMessage = getValidationMessage(qty, possibleValues);

                    return false;

                function getValidationMessage()
                {
                    var downValue = controlHelper.getDownValue(qty, possibleValues);
                    var upValue = controlHelper.getUpValue(qty, possibleValues);

                    var nearestMsg = (downValue === upValue)
                        ? $.mage.__(validationMessages.NEAREST_VALUE_SINGLE)
                        : $.mage.__(validationMessages.NEAREST_VALUE_MULTIPLE);

                    nearestMsg = nearestMsg.replace('%1', downValue);

                    if (downValue !== upValue) {
                        nearestMsg = nearestMsg.replace('%2', upValue);
                    }

                    var msg = $.mage.__(validationMessages.NOT_ALLOWED_VALUE).replace('%1', qty);

                    return '<p>' + msg + ' </p>' + '<p>' + nearestMsg + '</p>';
                }
            },
                function () {
                    return this.itemQtyErrorMessage;
                },
                false);


            return originalWidget;
        };
    }
);
