/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define(
    [
        'jquery',
        'jquery/ui',
        "Aitoc_ProductUnitsAndQuantities/js/quantities/controls/helper",
        "Aitoc_ProductUnitsAndQuantities/js/quantities/controls/base-separated"
    ],
    function (jQuery, jQueryUi, helper) {

        jQuery.widget('puq.uiSlider', jQuery.puq.baseControlSeparated, {
            controlContainerClass: 'aitoc-puq-control-container',
            // check if not repeat custom control functions
            createCustomControl: function (element, initialValue) {
                var sliderHandle = jQuery('<div class="aitoc-puq-control-handler ui-slider-handle"></div>');
                var slider = jQuery('<div class="aitoc-puq-control aitoc-puq-control-slider"></div>').append(sliderHandle);
                var sliderContainer = jQuery('<div></div>').addClass(this.controlContainerClass).append(slider);
                var currentValue = initialValue;
                // var currentValue = this.element.val();
                var nearest = currentValue;
                var uiValue;

                var useQuantities = this.options.quantities;

                slider.slider({
                    orientation: 'horizontal',
                    animate: true,
                    min: useQuantities[0],
                    max: useQuantities[useQuantities.length - 1],
                    value: currentValue,
                    disabled: element.prop('disabled'),
                    step: this.options.step ? this.options.step : 0.01,

                    create: function () {
                        sliderHandle.text(jQuery(this).slider("value"));
                    },

                    slide: function (event, ui) {
                        uiValue = ui.value;
                        nearest = helper.getNearestValue(uiValue, useQuantities);
                        sliderHandle.text(nearest);
                    },

                    change: function (event, ui) {
                        var uiValue = ui.value;
                        nearest = helper.getNearestValue(uiValue, useQuantities);

                        if (uiValue !== nearest) {
                            slider.slider('value', nearest);
                        }

                        sliderHandle.text(nearest);
                    }
                });

                this.customControl = slider;
                this.controlContainer = sliderContainer;
            },

            onChangeSource: function () {
                var $customControl = this.customControl,
                    $sourceControl = this.sourceControl;

                var customControlValue = $customControl.slider('value').toString();
                var elementVal = $sourceControl.val();

                if (elementVal !== customControlValue) {
                    $customControl.slider('value', elementVal);
                }
            },

            onChangeCustom: function () {
                var $customControl = this.customControl,
                    $sourceControl = this.sourceControl;

                var elementVal = $sourceControl.val();
                var customControlValue = $customControl.slider('value').toString();

                if (elementVal !== customControlValue) {
                    $sourceControl.val(customControlValue);
                    $sourceControl.change();
                }
            },

            bindSourceToCustom: function ($sourceControl, $customControl) {
                var callback = this.getMethodAsCallback(this.onChangeCustom);
                this.onCusotmChangeCallback = callback;

                $customControl.on('slidechange', callback);
            },

            removeCustomControl: function (customControl) {
                jQuery(customControl).slider('destroy');
                this.controlContainer.remove();
            },

            getValue: function () {
                return this.customControl.slider('value');
            },
            setValue: function (newValue) {
                return this.customControl.slider('value', newValue);
            },

            setOptionDisabled: function (value) {
                this._super(value);
                var customControl = this.customControl;
                customControl.slider('option', 'disabled', value);
            }
        });

        return jQuery.puq.uiSlider;
    }
);
