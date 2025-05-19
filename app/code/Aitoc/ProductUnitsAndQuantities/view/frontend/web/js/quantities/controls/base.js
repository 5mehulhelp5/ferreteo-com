/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define(
    [
        'jquery',
        'jquery/ui',
        "Aitoc_ProductUnitsAndQuantities/js/quantities/get-method-as-callback",
        "Aitoc_ProductUnitsAndQuantities/js/quantities/controls/helper",
        "Aitoc_ProductUnitsAndQuantities/js/quantities/validation-rules-helper",
        "Aitoc_ProductUnitsAndQuantities/js/quantities/min-max-step-props-helper"
    ],
    function (jQuery, jQueryUi, getMethodAsCallback, helper, validationRulesHelper, minMaxStepPropsHelper) {
        jQuery.widget('puq.baseControl', {
            controlContainer: null,
            customControl: null,
            onSourceChangeCallback: null,
            onCustomChangeCallback: null,
            controlContainerClass: 'aitoc-puq-control-container',
            sourceInitialMinMaxStep: null,
            sourceInitialValidationRules: null,

            _create: function () {
                var element = this.element;

                var initialAdjustedValue = this.getFixedByMinMaxIncQtyElementValue(element);
                this.setElementValue(element, initialAdjustedValue);


                var min = this.options.min,
                    max = this.options.max,
                    step = this.options.step;

                this.saveAndApplyMinMaxStepProps(element, min, max, step);
                this.saveAndApplyConstraintsIfRequired(element, this.options);

                //todo: make control with containers
                this.addControlContainerClasses(element);
                this.placeCustomControl(element, initialAdjustedValue, min, max, step);
            },

            saveAndApplyMinMaxStepProps: function (element, min, max, step) {
                this.saveMinMaxStepProps(element);
                this.applyMinMaxStepProps(element, min, max, step);
            },

            applyMinMaxStepProps: function (element, min, max, step) {
                minMaxStepPropsHelper.setMinMaxStepProps(element, min, max, step);
            },

            saveMinMaxStepProps: function (element) {
                this.sourceInitialMinMaxStep = minMaxStepPropsHelper.getMinMaxStepPropsAsObject(element);
            },

            setElementValue: function (element, value) {
                element.val(value);
            },

            getFixedByMinMaxIncQtyElementValue: function (element) {
                var srcValue = this.getElementValue(element);
                return this.getFixedByMinMaxIncQtyValue(srcValue);
            },

            saveAndApplyConstraintsIfRequired: function (element, options) {
                var isElementHaveValidator = this.isElementHaveValidator(element);

                if (!isElementHaveValidator) {
                    return;
                }

                this.saveElementValidationRules(element);

                var min = options.min, max = options.max, step = options.step;
                var itemValidationRuleName = this.getItemValidationRuleName(options);
                this.setValidationRules(element, min, max, step, options.quantities, itemValidationRuleName);
            },

            getItemValidationRuleName: function (options) {
                return options.itemValidationRuleName;
            },

            setValidationRules: function (element, min, max, step, quantities, validationRulesNames) {
                validationRulesHelper.setValidationRules(element, min, max, step, quantities, validationRulesNames);
            },

            isElementHaveValidator: function (element) {
                return validationRulesHelper.isElementHaveValidator(element);
            },

            saveElementValidationRules: function (element) {
                this.sourceInitialValidationRules = this.getElementValidationRules(element);
            },

            getElementValidationRules: function (element) {
                return validationRulesHelper.getElementValidationRules(element);
            },

            restoreElementValidationRules: function (element, initialConstraints) {
                validationRulesHelper.restoreElementValidationRules(element, initialConstraints);
            },

            restoreElementValidationRulesIfRequired: function (element) {
                if (!this.sourceInitialValidationRules) {
                    return;
                }

                this.restoreElementValidationRules(element, this.sourceInitialValidationRules);
            },

            getElementValue: function (element) {
                return parseFloat(element.val());
            },

            _destroy: function () {
                var element = this.element;

                this.restoreMinMaxStepProps(element, this.sourceInitialMinMaxStep);
                this.restoreElementValidationRulesIfRequired(element);
                this.removeControlContainerClasses(element);
                this.displaceCustomControl(element);
            },

            restoreMinMaxStepProps: function (element, minMaxStepObject) {
                minMaxStepPropsHelper.setMinMaxStepPropsByObject(element, minMaxStepObject);
            },

            getFixedByMinMaxIncQtyValue: function (srcInitialValue) {
                var possibleValues = this.getPossibleValues();
                return helper.getNearestValue(srcInitialValue, possibleValues);
            },

            getPossibleValues: function () {
                return this.options.quantities;
            },

            createCustomControl: function () {
                throw new Error('Method createCustomControl() should be defined in child control.');
            },

            addControlContainerClasses: function (element) {
                var controlContainer = this.getControlContainer(element);
                controlContainer.addClass(this.controlContainerClass);
            },

            removeControlContainerClasses: function (element) {
                var controlContainer = this.getControlContainer(element);
                controlContainer.removeClass(this.controlContainerClass);
            },

            getControlContainer: function ($element) {
                if (this.controlContainer === null) {
                    this.controlContainer = $element.closest('.control, .details-qty');
                }

                return this.controlContainer;
            },

            removeCustomControl: function () {
                this.customControl.remove();
            },

            showOrigControl: function (element) {
                element.show();
            },

            // todo: remove duplicates if possible
            getMethodAsCallback: function (method) {
                return getMethodAsCallback(method, this);
            },

            highlight: function () {
                this.customControl.addClass('mage-error');
            },

            _setOption: function (key, value) {
                this._super(key, value);

                if (key === "disabled") {
                    this.setOptionDisabled(value);
                }
            },

            setOptionDisabled: function (value) {
                var customControlContainer = this.controlContainer;

                customControlContainer.attr("aria-disabled", value);

                value
                    ? customControlContainer.addClass("ui-state-disabled")
                    : customControlContainer.removeClass("ui-state-disabled");
            },

            value: function (newValue) {
                if (newValue === undefined) {
                    return this.getValue();
                }

                var resultValue = this.getFixedByMinMaxIncQtyValue(newValue);

                if (resultValue === this.getValue()) {
                    return;
                }

                this.setValue(resultValue);
            }
        });

        return jQuery.puq.uiBase;
    }
);
