/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    "Aitoc_ProductUnitsAndQuantities/js/quantities/ui-widget-helper",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/min-max-step-props-helper",
    "Aitoc_ProductUnitsAndQuantities/js/constants/replace-qty"
], function (uiWidgetHelper, minMaxStepPropsHelper, REPLACE_QTY) {
    return {
        applyCustomControlIfRequired: function (customControlConfig, qtyElement, optionValue) {
            destroyQtyFieldCustomControlIfRequired(customControlConfig, qtyElement);
            applyCustomControlToBundleOptionIfRequired(customControlConfig, qtyElement);

            updateCustomControlAttributesIfRequired(customControlConfig, qtyElement, optionValue);
        }
    };

    function updateCustomControlAttributesIfRequired(customControlConfig, qtyElement, optionValue)
    {
        updateCustomControlValueIfRequired(customControlConfig, qtyElement, optionValue);
        updateDisabledState(customControlConfig, qtyElement);
        updateMinMaxStepIfRequired(customControlConfig, qtyElement);
    }

    function updateMinMaxStepIfRequired(customControlConfig, qtyElement)
    {
        if (!customControlConfig) {
            return;
        }

        minMaxStepPropsHelper.setMinMaxStepProps(qtyElement, customControlConfig.min, customControlConfig.max, customControlConfig.step)
    }

    function updateDisabledState(customControlConfig, qtyElement)
    {
        var disabled = qtyElement.prop('disabled');

        var uiWidgetName = getUiWidgetNameByConfig(customControlConfig);

        updateDisabledStateForQty(qtyElement, uiWidgetName, disabled);
    }

    function updateDisabledStateForQty(qtyField, uiWidgetName, disableValue)
    {
        if (!uiWidgetName) {
            return;
        }

        qtyField[uiWidgetName]('option', 'disabled', disableValue)
    }

    function updateCustomControlValueIfRequired(customControlConfig, qtyElement, optionValue)
    {
        if (!isRequiredCustomControlValueUpdate(customControlConfig, qtyElement, optionValue)) {
            return;
        }

        setCustomControlValueByControl(customControlConfig, qtyElement, optionValue);
    }

    function destroyQtyFieldCustomControlIfRequired(customControlConfig, qtyElement)
    {
        if (!isRequiredQtyControlDestroy(customControlConfig, qtyElement)) {
            return;
        }

        destroyQtyFieldCustomControl(customControlConfig, qtyElement);
    }

    function isRequiredQtyControlDestroy(customControlConfig, qtyElement)
    {
        return uiWidgetHelper.isRequiredCustomControlDestroy(qtyElement, customControlConfig);
    }

    function destroyQtyFieldCustomControl(customControlConfig, qtyElement)
    {
        destroyCustomControlIfRequired(qtyElement);
    }

    function destroyCustomControlIfRequired(qtyField)
    {
        if (isDestroyCustomControlRequired(qtyField)) {
            destroyCustomControl(qtyField);
        }
    }

    function destroyCustomControl(element)
    {
        var customControlName = uiWidgetHelper.getCustomControlNameOrNull(element);

        jQuery(element)[customControlName]('destroy');
    }

    function isDestroyCustomControlRequired(qtyField)
    {
        return uiWidgetHelper.isCustomControl(qtyField);
    }

    function setCustomControlValueByControl(customControlConfig, qtyElement, optionValue)
    {
        uiWidgetHelper.setControlValue(qtyElement, optionValue);
    }

    function isRequiredCustomControlValueUpdate(customControlConfig, qtyElement, optionValue)
    {
        var sourceCustomControlValue = getCustomControlValueByControl(qtyElement);

        if (sourceCustomControlValue === null) {
            return false;
        }

        return sourceCustomControlValue !== optionValue;
    }

    function getCustomControlValueByControl($customControl)
    {
        var customControlName = uiWidgetHelper.getCustomControlNameOrNull($customControl);

        if (!customControlName) {
            return null;
        }

        return parseFloat($customControl[customControlName]('value'));
    }

    function applyCustomControlToBundleOptionIfRequired(customControlConfig, qtyElement)
    {
        if (!uiWidgetHelper.isRequiredCustomControlApply(qtyElement, customControlConfig)) {
            return;
        }

        applyCustomControlIfRequired(qtyElement, customControlConfig);
    }

    function applyCustomControlIfRequired(qtyField, selectionQtyFieldConfig)
    {
        if (isApplyCustomControlRequired(selectionQtyFieldConfig)) {
            applyCustomControl(qtyField, selectionQtyFieldConfig);
        }
    }

    function isApplyCustomControlRequired(selectionQtyFieldConfig)
    {
        if (!selectionQtyFieldConfig) {
            return false;
        }

        return selectionQtyFieldConfig.replace_qty !== REPLACE_QTY.OFF;

    }

    function getUiWidgetNameByConfig(config)
    {
        return config ? uiWidgetHelper.getUiWidgetNameByReplaceQtyValue(config.replace_qty) : null;
    }

    function applyCustomControl(element, newConfig)
    {
        uiWidgetHelper.instantiateQtyWidget(element, newConfig);
    }
});
