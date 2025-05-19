/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    'jquery',
    'jquery/ui',
    'Aitoc_ProductUnitsAndQuantities/js/quantities/current-product-config',
    'Aitoc_ProductUnitsAndQuantities/js/quantities/current-product-helper',
    "Aitoc_ProductUnitsAndQuantities/js/constants/config-keys",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/constants/bundle-product-option-types",
    "Aitoc_ProductUnitsAndQuantities/js/constants/replace-qty",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/ui-widget-helper",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/mode-helper/qty-selectors/bundle-child",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/grouped-product-helper",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/qty-control-applier",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/bundle-product-helper",
    'priceUtils'
], function (
    jQuery,
    jQueryUi,
    currentProductConfig,
    currentProductHelper,
    CONFIG_KEYS,
    BUNDLE_PRODUCT_OPTION_TYPES,
    REPLACE_QTY,
    uiWidgetHelper,
    bundleChildSelector,
    groupedProductHelper,
    qtyControlApplier,
    bundleProductHelper,
    utils
) {
    'use strict';

    return function () {
        jQuery.widget('mage.priceBundle', jQuery.mage.priceBundle, {
            options: {
                optionTemplate: '<%- data.label %>' +
                '<% if (data.finalPrice.value) { %>' +
                ' +<%- data.finalPrice.formatted %>' +
                '<% } %>' +
                '<% if (data.finalPrice.units) { %>' +
                ' +<%- data.finalPrice.units %>' +
                '<% } %>'
            },
            _onBundleOptionChanged: function (event) {
                this._super(event);

                var bundleOption = jQuery(event.target);

                if (!isUpdateQtyFieldRequired(bundleOption)) {
                    return;
                }

                var bundleOptionConfig = this.options.optionConfig;

                var customControlConfig = bundleOptionToCustomControlConfig(bundleOption);
                var qtyElement = bundleOptionToQtyElement(bundleOption);
                var optionValue = getOptionValue(bundleOption, bundleOptionConfig);

                qtyControlApplier.applyCustomControlIfRequired(customControlConfig, qtyElement, optionValue);
            }
        });

        return jQuery.mage.priceBundle;
    };

    function bundleOptionToCustomControlConfig(bundleOption)
    {
        return getSelectionQtyFieldConfig(bundleOption);
    }

    function getSelectionQtyFieldConfig(bundleOption)
    {
        var selectionId = getSelectionIdByElement(bundleOption);

        if (!selectionId) {
            return null;
        }

        return currentProductHelper.getLinkedProductConfig(selectionId);
    }

    function getSelectionIdByElement(element)
    {
        return groupedProductHelper.getSelectionIdByElement(element);
    }

    function bundleOptionToQtyElement(bundleOption)
    {
        return getSourceControl(bundleOption);
    }

    function getSourceControl(bundleOption)
    {
        return bundleOption.data('qtyField');
    }

    function getOptionValue(element, config)
    {
        var optionQty = 0;
        var optionType = element.prop('type');
        var optionValue = element.val() || null;
        var optionId = utils.findOptionId(element[0]);
        var optionConfig = config.options[optionId].selections;

        switch (optionType) {
            case BUNDLE_PRODUCT_OPTION_TYPES.RADIO:
            case BUNDLE_PRODUCT_OPTION_TYPES.SELECT_ONE:
                if (optionValue) {
                    optionQty = optionConfig[optionValue].qty || 0;
                }
                break;
            case BUNDLE_PRODUCT_OPTION_TYPES.CHECKBOX:
                optionQty = optionConfig[optionValue].qty || 0;
                break;
            case BUNDLE_PRODUCT_OPTION_TYPES.HIDDEN:
                optionQty = optionConfig[optionValue].qty || 0;
                break;
        }

        return optionQty;
    }

    function isUpdateQtyFieldRequired(bundleOption)
    {
        var optionType = bundleProductHelper.getElementOptionType(bundleOption);

        switch (optionType) {
            case BUNDLE_PRODUCT_OPTION_TYPES.RADIO:
                return isCheckedRadio(bundleOption);

            case BUNDLE_PRODUCT_OPTION_TYPES.HIDDEN:
            case BUNDLE_PRODUCT_OPTION_TYPES.SELECT_ONE:
                return true;

            case BUNDLE_PRODUCT_OPTION_TYPES.SELECT_MULTIPLE:
            case BUNDLE_PRODUCT_OPTION_TYPES.CHECKBOX:
                return false;

            default:
                throw new Error('Unknown bundle option type:' + optionType);
        }
    }

    function isCheckedRadio(element)
    {
        return element.prop('checked');
    }
});
