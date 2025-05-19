/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    'jquery',
    'jquery/ui',
    "Aitoc_ProductUnitsAndQuantities/js/constants/config-keys",
    "Aitoc_ProductUnitsAndQuantities/js/constants/replace-qty",
    "Aitoc_ProductUnitsAndQuantities/js/constants/qty-types",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/constants/known-custom-controls",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/constants/block-config-mode",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/constants/validation-rules",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/quantity-values-helper",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/controls/all"
], function (
    jQuery,
    jQueryUi,
    CONFIG_KEYS,
    REPLACE_QTY,
    QTY_TYPES,
    KNOWN_CUSTOM_CONTROLS,
    BLOCK_CONFIG_MODE,
    VALIDATION_RULES,
    quantityValuesHelper
) {
    return {
        setControlValue: function ($control, value) {
            var controlName = this.getCustomControlNameOrNull($control);
            $control[controlName]('value', value);
        },

        isInstanceOfWidget: function ($element, namespace, name) {
            var fullWidgetName = getFullWidgetName(namespace, name);
            var selector = getWidgetDataSelector(fullWidgetName);

            return $element.is(selector);

            function getFullWidgetName(namespace, name)
            {
                return namespace + firstUpperCase(name);

                function firstUpperCase(input)
                {
                    return input[0].toUpperCase() + input.substr(1);
                }
            }

            function getWidgetDataSelector(fullWidgetName)
            {
                return ":data('" + fullWidgetName + "')";
            }
        },

        isInstanceOfPuqWidget: function ($element, name) {
            return this.isInstanceOfWidget($element, 'puq', name);
        },

        instantiatePriceWidget: function (priceElement, config, getQtyCallback) {
            var qtyElement = this.getQtyElementOrThrow(priceElement, getQtyCallback);

            return this.instantiateQtyWidget(qtyElement, config);
        },

        instantiateQtyWidget: function (qtyElement, config) {
            var uiWidgetName = this.getUiWidgetNameByReplaceQtyValue(config.replace_qty);

            var controlConfig = this.getQtyWidgetConfig(qtyElement, config);
            // currentValue: currentValue

            //render custom element
            qtyElement[uiWidgetName](controlConfig);
        },

        getQtyWidgetConfig: function (qtyElement, config) {
            var quantities = this.getQuantitiesAsArrayOfFloat(config);
            var min = quantities[0];
            var max = quantities[quantities.length - 1];
            var step = this.getStepByConfig(config);
            //get original attributes
            var elementOptions = {
                'id': qtyElement.attr('id'),
                'class': qtyElement.attr('class'),
                'name': qtyElement.attr('name'),
                'data-cart-item-id': qtyElement.attr('data-cart-item-id')
            };

            var validationRuleNames = this.getItemValidationRuleName(config);

            //var currentValue = qtyElement.val();

            return {
                quantities: quantities,
                min: min,
                max: max,
                step: step,
                isQtyDecimal: config[CONFIG_KEYS.IS_QTY_DECIMAL],
                elementOptions: elementOptions,
                itemValidationRuleName: validationRuleNames,
                allow_zero: config.allow_zero
            };
        },

        getItemValidationRuleName: function (config) {
            return (config.mode !== BLOCK_CONFIG_MODE.grouped_view)
                ? VALIDATION_RULES.VALIDATE_ITEM_QUANTITY
                : VALIDATION_RULES.VALIDATE_GROUPED_ITEM_QUANTITY;
        },

        getStepByConfig: function (config) {
            return config[CONFIG_KEYS.QTY_TYPE] === QTY_TYPES.DYNAMIC ? Number(config[CONFIG_KEYS.QTY_INCREMENT]) : null;
        },

        // todo: have same method in other classes
        getQtyElementOrNull: function (element, getQtyCallback) {
            var qtyElement = this.getQtyElement(element, getQtyCallback);

            if (qtyElement.length !== 1) {
                return null;
            }

            return qtyElement;
        },

        getQtyElementOrThrow: function (element, getQtyCallback) {
            var qtyElement = this.getQtyElement(element, getQtyCallback);

            if (qtyElement.length !== 1) {
                throw new Error('No qtyElement.');
            }

            return qtyElement;
        },

        getQtyElement: function (element, getQtyCallback) {
            return getQtyCallback(element);
        },

        getUiWidgetNameByReplaceQtyValue: function (replaceQty) {
            if (replaceQty === REPLACE_QTY.OFF) {
                return null;
            }

            var replaceQtyToUiWidgetMap = this.getReplaceQtyToUiWidgetMap();

            if (!replaceQtyToUiWidgetMap[replaceQty]) {
                throw new Error('Unknown replaceQty value: ' + replaceQty);
            }

            return replaceQtyToUiWidgetMap[replaceQty];
        },

        getReplaceQtyToUiWidgetMap: function () {
            var replaceQtyToUiWidgetMap = {};
            replaceQtyToUiWidgetMap[REPLACE_QTY.DROPDOWN] = 'uiDropdown';
            replaceQtyToUiWidgetMap[REPLACE_QTY.SLIDER] = 'uiSlider';
            replaceQtyToUiWidgetMap[REPLACE_QTY.PLUS_MINUS] = 'uiPlusMinus';
            replaceQtyToUiWidgetMap[REPLACE_QTY.ARROWS] = 'uiArrows';

            return replaceQtyToUiWidgetMap;
        },

        getQuantitiesAsArrayOfFloat: function (config) {
            return quantityValuesHelper.getQuantitiesAsArrayOfFloat(config);
        },

        isRequiredCustomControlApply: function (element, newConfig) {
            if (!this.isConfigAndCurrentControlTypesMatched(element, newConfig)) {
                return true;
            }

            return !this.isConfigAndCurrentControlConfigTheSameObject(element, newConfig);
        },

        isRequiredCustomControlDestroy: function (element, newConfig) {
            if (!this.isConfigAndCurrentControlTypesMatched(element, newConfig)) {
                return true;
            }

            return !this.isConfigAndCurrentControlConfigTheSameObject(element, newConfig);
        },

        isConfigAndCurrentControlConfigTheSameObject: function (element, newConfig) {
            var elementConfig = this.getCustomControlConfig(element);
            var newElementConfig = newConfig ? this.getQtyWidgetConfig(element, newConfig) : null;

            return this.isConfigsTheSame(elementConfig, newElementConfig);
        },

        isConfigsTheSame: function (config1, config2) {
            if (config1 == null && config2 == null) {
                return true;
            }

            if (config1 === null && config2 !== null) {
                return false;
            }

            if (config1 !== null && config2 === null) {
                return false;
            }

            return this.isObjectsEq(config1.quantities, config2.quantities);
        },

        isObjectsEq: function (obj1, obj2) {
            return JSON.stringify(obj1) === JSON.stringify(obj2);
        },

        getCustomControlConfig: function (element) {
            var customControlName = this.getCustomControlNameOrNull(element);

            if (!customControlName) {
                return null;
            }

            return element[customControlName]('option');
        },

        isConfigAndCurrentControlTypesMatched: function (element, newConfig) {
            var currentControlName = this.getCustomControlNameOrNull(element);

            if (
                (currentControlName === null) && (newConfig !== null)
                || (currentControlName !== null) && (newConfig === null)
            ) {
                return false;
            }

            var uiWidgetName = newConfig ? this.getUiWidgetNameByReplaceQtyValue(newConfig.replace_qty) : null;

            return uiWidgetName === currentControlName;
        },

        isCustomControl: function (element) {
            return this.getCustomControlNameOrNull(element) !== null;
        },

        getCustomControlNameOrNull: function (element) {
            for (var key in KNOWN_CUSTOM_CONTROLS) {
                var widgetName = KNOWN_CUSTOM_CONTROLS[key];

                if (this.isInstanceOfPuqWidget(element, widgetName)) {
                    return widgetName;
                }
            }

            return null;
        },

        highlightCustomControl: function (element) {
            var customControlName = this.getCustomControlNameOrNull(element);

            if (!customControlName) {
                return null;
            }

            return element[customControlName]('highlight');
        }
    }
});
