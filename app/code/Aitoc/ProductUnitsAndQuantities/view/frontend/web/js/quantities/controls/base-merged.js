/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define(
    [
        'jquery',
        'jquery/ui',
        "Aitoc_ProductUnitsAndQuantities/js/quantities/constants/keyboard-codes",
        "Aitoc_ProductUnitsAndQuantities/js/quantities/controls/helper",
        "Aitoc_ProductUnitsAndQuantities/js/quantities/controls/base"
    ],
    function (jQuery, jQueryUi, KEYBOARD_CODES, controlsHelper) {

        jQuery.widget('puq.baseControlMerged', jQuery.puq.baseControl, {
            _create: function () {
                this._super();

                this.addKeyUpDownMouseWheelHandlerIfRequired(this.element, this.options);
            },

            addKeyUpDownMouseWheelHandlerIfRequired: function (element, options) {
                if (this.isStepDefined(options) && !options.allow_zero) {
                    return;
                }

                this.bindKeyboard(element, options);
                this.bindMouseWheel(element, options);
            },

            bindKeyboard: function (element, options) {
                var self = this;

                element.keydown(function (e) {
                    switch (e.which) {
                        case KEYBOARD_CODES.DOWN:
                            e.preventDefault();
                            self.setDownValueIfRequired(element, options);
                            break;

                        case KEYBOARD_CODES.UP:
                            e.preventDefault();
                            self.setUpValueIfRequired(element, options);
                            break;
                    }
                });
            },

            bindMouseWheel: function (element, options) {
                element.on('mousewheel', function (e) {
                    e.preventDefault();
                    (e.originalEvent.wheelDelta < 0)
                        ? self.setDownValueIfRequired(element, options)
                        : self.setUpValueIfRequired(element, options);
                });
            },

            setDownValueIfRequired: function (element, options) {
                var currentVal = parseFloat(element.val()),
                    downValue = controlsHelper.getDownValue(currentVal, options.quantities);

                if (currentVal === downValue) {
                    return;
                }

                element.val(downValue);
            },

            setUpValueIfRequired: function (element, options) {
                var currentVal = parseFloat(element.val()),
                    upValue = controlsHelper.getUpValue(currentVal, options.quantities);

                if (currentVal === upValue) {
                    return;
                }

                element.val(upValue);
            },

            isStepDefined: function (options) {
                var step = options.step;

                return Boolean(step);
            },

            placeCustomControl: function (element, initialValue, min, max, step) {
                this.addHandlers(element);
                this.customControl = element;
                this.element.val(initialValue);
                this.controlContainer.addClass(this.controlClass);
            },

            addHandlers: function (element) {
                this.throwNowImplemented();
            },

            displaceCustomControl: function (element) {
                this.removeHandlers(element);
                this.controlContainer.removeClass(this.controlClass);
            },

            removeHandlers: function (element) {
                this.throwNowImplemented();
            },

            setValue: function (newValue) {
                this.customControl.val(newValue);
            },

            getValue: function () {
                return parseFloat(this.customControl.val());
            },

            min: function ( newValue ) {
                if ( newValue === undefined ) {
                    return this.element.prop('min');
                }

                this.element.prop('min');
            },

            max: function ( newValue ) {
                if ( newValue === undefined ) {
                    return this.element.prop('max');
                }

                this.element.prop('max');
            },

            step: function ( newValue ) {
                if ( newValue === undefined ) {
                    return this.element.prop('step');
                }

                this.element.prop('step');
            },

            highlight: function () {
                //merged controls text highlighted by mixin for option change called after initialization
            }
        });

        return jQuery.puq.baseControlSeparated;
    }
);
