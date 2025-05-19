/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define(
    ['jquery', 'jquery/ui', "Aitoc_ProductUnitsAndQuantities/js/quantities/controls/base"],
    function (jQuery) {

        jQuery.widget('puq.baseControlSeparated', jQuery.puq.baseControl, {
            placeCustomControl: function (element, initialValue, min, max, step) {
                this.createCustomControl(element, initialValue, min, max, step);
                var customControl = this.customControl;
                this.bindCustomControl(element, customControl);
                this.addCustomControl(element, customControl);
                this.hideOrigControl(element);
            },

            displaceCustomControl: function (element) {
                var customControl = this.customControl;

                this.removeCustomControl(customControl);
                this.unbindCustomControl(element, customControl);
                this.showOrigControl(element);
            },

            bindCustomControl: function ($sourceControl, $customControl) {
                this.customControl = $customControl;
                this.sourceControl = $sourceControl;

                this.bindCustomToSource($customControl, $sourceControl);
                this.bindSourceToCustom($sourceControl, $customControl);
            },

            unbindCustomControl: function ($sourceControl, $customControl) {
                this.unbindCustomToSource($customControl, $sourceControl);
                this.unbindSourceToCustom($sourceControl, $customControl);
            },

            customControl: null,
            sourceControl: null,

            bindCustomToSource: function ($customControl, $sourceControl) {
                var callback = this.getMethodAsCallback(this.onChangeSource);
                this.onSourceChangeCallback = callback;

                $sourceControl.change(callback);
            },

            unbindCustomToSource: function ($customControl, $sourceControl) {
                $sourceControl.off('change', this.onSourceChangeCallback);
            },

            onChangeSource: function () {
                var $customControl = this.customControl,
                    $sourceControl = this.sourceControl;

                var customControlValue = $customControl.val();
                var elementVal = $sourceControl.val();

                if (elementVal && (elementVal !== customControlValue)) {
                    $customControl.val(elementVal);
                    $customControl.change();
                }
            },

            bindSourceToCustom: function ($sourceControl, $customControl) {
                var callback = this.getMethodAsCallback(this.onChangeCustom);
                this.onCusotmChangeCallback = callback;

                $customControl.change(callback);
            },

            unbindSourceToCustom: function ($sourceControl, $customControl) {
                $customControl.off('change', this.onCusotmChangeCallback);
            },

            onChangeCustom: function () {
                var $customControl = this.customControl,
                    $sourceControl = this.sourceControl;

                var customControlValue = $customControl.val();
                var elementVal = $sourceControl.val();

                if (customControlValue && (elementVal !== customControlValue)) {
                    $sourceControl.val(customControlValue);
                    $sourceControl.change();
                }
            },

            addCustomControl: function (element, customControl) {
                element.after(customControl);
            },
            hideOrigControl: function (element) {
                element.hide();
            }
        });

        return jQuery.puq.baseControlSeparated;
    }
);
