/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define(
    ['jquery', 'jquery/ui', "Aitoc_ProductUnitsAndQuantities/js/quantities/controls/base-separated"],
    function (jQuery) {

        jQuery.widget('puq.uiDropdown', jQuery.puq.baseControlSeparated, {
            controlContainerClass: 'aitoc-puq-control-container aitoc-puq-control-dropdown',
            createCustomControl: function (element, initialValue) {
                var control = document.createElement('select');
                control.classList.add('aitoc-puq-control');//todo: duplicate from controlContainerClass?
                control.disabled = element.prop('disabled');

                var useQuantities = this.options.quantities;

                useQuantities.forEach(function (item) {
                    var option = document.createElement('option');
                    option.value = item;
                    option.innerText = item;
                    control.append(option);
                });

                control.value = initialValue;
                var customControl = jQuery(control);
                var controlContainer = jQuery('<div></div>').addClass(this.controlContainer).append(this.customControl);
                this.customControl = customControl;
                this.controlContainer = controlContainer;
            },
            setValue: function (newValue) {
                this.customControl.val(newValue);
                this._trigger("change");
            },
            getValue: function () {
                return parseFloat(this.customControl.val());
            },
            setOptionDisabled: function (value) {
                this._super(value);
                var customControl = this.customControl;
                customControl.prop('disabled', value);
            }
        });

        return jQuery.puq.uiDropdown;
    }
);
