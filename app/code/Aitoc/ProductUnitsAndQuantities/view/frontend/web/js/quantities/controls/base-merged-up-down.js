/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define(
    [
        'jquery',
        'jquery/ui',
        'Aitoc_ProductUnitsAndQuantities/js/quantities/controls/helper',
        "Aitoc_ProductUnitsAndQuantities/js/quantities/controls/base-merged"
    ],
    function (jQuery, jQueryUi, helper) {

        jQuery.widget('puq.baseControlMergedUpDown', jQuery.puq.baseControlMerged, {
            minusControl: null,
            plusControl: null,

            addHandlers: function (element) {
                var useQuantities = this.options.quantities;

                this.setUpdateControlDisabledStateIfRequired(element);

                var minusControl = this.createMinusControl(element, useQuantities);
                var plusControl = this.createPlusControl(element, useQuantities);

                this.minusControl = minusControl;
                this.plusControl = plusControl;

                element
                    .before(minusControl)
                    .after(plusControl);
            },

            setUpdateControlDisabledStateIfRequired: function (element) {
                var $controlContainer = this.getControlContainer(element);

                var elementIdDisabled = element.prop('disabled');

                elementIdDisabled ? $controlContainer.addClass('ui-state-disabled')
                    : $controlContainer.removeClass('ui-state-disabled');

                this._setOption('disabled', elementIdDisabled);
            },

            removeHandlers: function (element) {
                this.plusControl.remove();
                this.minusControl.remove();
            },

            getDownControlHtml: function () {
                this.throwNowImplemented();
            },

            getUpControlHtml: function () {
                this.throwNowImplemented();
            },

            createMinusControl: function (element, useQuantities) {
                var controlHtml = this.getDownControlHtml();

                return this.baseCreateDownControl(element, useQuantities, controlHtml);
            },

            createUpDownControl: function (element, useQuantities, html, isUpdateRequiredCallback, newValueCallback) {
                var control = jQuery(html);
                var self = this;

                control.click(function () {
                    if (self.options.disabled) {
                        return false;
                    }

                    var currentQty = parseFloat(element.val());

                    if (!isUpdateRequiredCallback(currentQty, useQuantities)) {
                        return false;
                    }

                    var newValue = newValueCallback(currentQty, useQuantities);

                    self.updateInputValueAndTriggerChange(element, newValue);
                    return false;
                });

                return control;
            },

            baseCreateUpControl: function (element, useQuantities, html) {
                self = this;

                var isNotMaximal = function (value, sortedArray) {
                    return self.isNotMaximal(value, sortedArray);
                };

                var getUpValue = function (value, sortedArray) {
                    return self.getUpValue(value, sortedArray);
                };

                return this.createUpDownControl(element, useQuantities, html, isNotMaximal, getUpValue);
            },

            baseCreateDownControl: function (element, useQuantities, html) {
                self = this;

                var isNotMinimal = function (value, sortedArray) {
                    return self.isNotMinimal(value, sortedArray);
                };

                var getDownValue = function (value, sortedArray) {
                    return self.getDownValue(value, sortedArray);
                };

                return this.createUpDownControl(element, useQuantities, html, isNotMinimal, getDownValue);
            },

            isNotMinimal: function (value, sortedArray) {
                return !this.isMinimal(value, sortedArray);
            },

            isMinimal: function (value, sortedArray) {
                return value === sortedArray[0];
            },

            isMaximal: function (value, sortedArray) {
                return value === sortedArray[sortedArray.length - 1];
            },

            isNotMaximal: function (value, sortedArray) {
                return !this.isMaximal(value, sortedArray);
            },

            getDownValue: function (value, sortedArray) {
                return helper.getDownValue(value, sortedArray);
            },

            getUpValue: function (value, sortedArray) {
                return helper.getUpValue(value, sortedArray);
            },

            createPlusControl: function (element, useQuantities) {
                var controlHtml = this.getUpControlHtml();

                return this.baseCreateUpControl(element, useQuantities, controlHtml);
            },

            updateInputValueAndTriggerChange: function ($input, value) {
                $input.val(value);
                $input.trigger('change');
            }
        });

        return jQuery.puq.baseControlMergedUpDown;
    }
);
