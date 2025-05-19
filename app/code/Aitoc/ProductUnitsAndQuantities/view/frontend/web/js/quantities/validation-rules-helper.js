/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define(['Aitoc_ProductUnitsAndQuantities/js/quantities/constants/validation-rules', 'jquery/validate'], function (VALIDATION_RULES) {
    return {

        isElementHaveValidator: function (element) {
            return Boolean(element.closest('form').data('validator'));
        },

        getElementValidationRules: function (element) {
            return element.rules();
        },

        setValidationRulesToQtyElement: function (element, validationRules) {
            element.rules('add', validationRules);
        },

        setValidationDataToQtyElement: function (element, validationRules) {
            element.data('validate', validationRules);
        },

        applyValidationRules: function (element, rules) {
            element.rules('remove');
            element.rules('add', rules);
        },

        setValidationRules: function (element, min, max, step, quantities, validationRuleName) {
            this.applyValidationConstraintsToQtyElement(element, min, max, step, quantities, validationRuleName);
        },

        applyValidationConstraintsToQtyElement: function (element, min, max, step, quantities, validationRulesNames) {
            var validationRules = this.getValidationRules(element, min, max, step, quantities, validationRulesNames);

            /*for (var index in validationRules) {
                var validationRule = validationRules[index];
                this.addValidationRule(validationRule);
            }
*/
            this.setValidationRulesToQtyElement(element, validationRules);
            this.setValidationDataToQtyElement(element, validationRules);
        },

        addValidationRule: function (element, validationRule) {
            this.setValidationRulesToQtyElement(element, validationRule);
            this.setValidationDataToQtyElement(element, validationRule);
        },

        getValidationRules: function (element, min, max, step, quantities, validationRuleName) {
            var rules = {};
            this.addItemOrGroupedItemValidationRule(rules, validationRuleName, min, max, step);
            this.addByQuantitiesValidationRule(rules, quantities);

            return rules;
        },

        addItemOrGroupedItemValidationRule: function (rules, validationRuleName, min, max, step) {
            rules[validationRuleName] = this.getItemOrGroupedItemValidationRuleParams(min, max, step);
        },

        getItemOrGroupedItemValidationRuleParams: function (min, max, step) {
            var validationRuleParam = {minAllowed: min, maxAllowed: max};

            if (step) {
                validationRuleParam['qtyIncrements'] = step;
            }

            return validationRuleParam;
        },

        addByQuantitiesValidationRule: function (rules, quantities) {
            rules[VALIDATION_RULES.VALIDATE_BY_QUANTITIES] = quantities;
        },

        restoreElementValidationRules: function (element, initialConstraints) {
            this.applyValidationRules(element, initialConstraints);
        }
    }
});
