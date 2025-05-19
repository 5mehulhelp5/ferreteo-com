/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    'jquery',
    'jquery/ui',
    "Aitoc_ProductUnitsAndQuantities/js/quantities/ui-widget-helper",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/controls/dropdown",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/controls/slider",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/controls/plus-minus",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/controls/arrows"
], function (jQuery, jQueryUi, uiWidgetHelper) {
    'use strict';

    return function (original) {
        if (!original) {
            return original;
        }
        jQuery.widget('mage.validation', jQuery.mage.validation, {
            options: {

                /**
                 * @param {*} error
                 * @param {HTMLElement} element
                 */
                errorPlacement: function (error, element) {
                    if (isCustomControl(element) && !isGroupedProductElement(element)) {
                        this.errorPlacementAppend(element, error);
                        return;
                    }


                    //fix for group of checkboxes
                    var dataValidate = element.attr('data-validate');

                    if ((dataValidate && dataValidate.indexOf('validate-one-checkbox-required-by-name') <= 0)
                        && element.is(':radio, :checkbox')
                    ) {
                        var nested = element.closest(this.radioCheckboxClosest);
                        var next = nested.next();

                        if (next.prop('class') === error.prop('class')) {
                            return;
                        }

                        nested.after(error);
                    }

                    if (original) {
                        return original.prototype.options.errorPlacement.apply(this, arguments);
                    } else {
                        this.parentValidation(error, element);
                    }
                },

                /**
                 * @param {*} error
                 * @param {*} element
                 */
                parentValidation: function (error, element) {
                    var errorPlacement = element,
                        fieldWrapper;

                    // logic for date-picker error placement
                    if (element.hasClass('_has-datepicker')) {
                        errorPlacement = element.siblings('button');
                    }
                    // logic for field wrapper
                    fieldWrapper = element.closest('.addon');

                    if (fieldWrapper.length) {
                        errorPlacement = fieldWrapper.after(error);
                    }
                    //logic for checkboxes/radio
                    if (element.is(':checkbox') || element.is(':radio')) {
                        errorPlacement = element.parents('.control').children().last();

                        //fallback if group does not have .control parent
                        if (!errorPlacement.length) {
                            errorPlacement = element.siblings('label').last();
                        }
                    }
                    //logic for control with tooltip
                    if (element.siblings('.tooltip').length) {
                        errorPlacement = element.siblings('.tooltip');
                    }
                    errorPlacement.after(error);
                },

                errorPlacementAppend: function (element, error) {
                    var $controlContainer = this.getControlContainer(element);
                    $controlContainer.append(error);
                },

                getControlContainer: function ($element) {
                    return $element.closest('.control');
                },

                highlight: function (element, errorClass) {
                    var $element = jQuery(element);

                    if (isCustomControl($element)) {
                        highlightCustomControl($element);
                    }

                    return original.prototype.options.highlight.apply(this, arguments);
                }
            }
        });

        return jQuery.mage.validation;
    };

    function isGroupedProductElement($element)
    {
        return $element.closest('#super-product-table').length === 1;
    }

    function isCustomControl($element)
    {
        return uiWidgetHelper.isCustomControl($element);
    }

    function highlightCustomControl($element)
    {
        uiWidgetHelper.highlightCustomControl($element);
    }
});
