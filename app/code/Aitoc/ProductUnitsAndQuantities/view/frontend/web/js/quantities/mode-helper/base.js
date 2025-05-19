/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    "jquery",
    "jquery/ui",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/ui-widget-helper",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/get-method-as-callback",

    "Aitoc_ProductUnitsAndQuantities/js/quantities/controls/dropdown",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/controls/slider",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/controls/plus-minus",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/controls/arrows",

    "Aitoc_ProductUnitsAndQuantities/js/constants/replace-qty"
], function (
    jQuery,
    jqueryUi,
    uiWidgetHelper,
    getMethodAsCallback,
    dropdownControl,
    sliderControl,
    plusMinusControl,
    arrowsControl,
    REPLACE_QTY
) {
    "use strict";

    jQuery.widget('puq.quantitiesBase', {
        _create: function () {
            var options = this.options;
            var element = this.element;

            if (options.replace_qty === REPLACE_QTY.OFF) {
                return;
            }

            var self = this;

            jQuery(document).ready(function () {
                self.replaceQty(options, element);
            });
        },

        // todo: should be here?
        throwNowImplemented: function () {
            throw new Error('Not implemented.');
        },

        replaceQty: function (config, element) {
            this.throwNowImplemented();
        },

        instantiateWidgetIfRequired: function (config, element, getQtyCallback) {
            if (!config) {
                return;
            }

            if (!this.isWidgetInstantiationRequired(element, config, getQtyCallback)) {
                return;
            }

            return this.instantiateWidget(element, config, getQtyCallback);
        },

        instantiateWidget: function (element, config, getQtyCallback) {
            return uiWidgetHelper.instantiatePriceWidget(element, config, getQtyCallback);
        },

        getMethodAsCallback: function (method) {
            return getMethodAsCallback(method, this);
        },

        isWidgetInstantiationRequired: function (element, config, getQtyCallback) {
            var qtyElement = this.getQtyElementOrNull(element, getQtyCallback);

            /*
                not throw because:
                 - parent product in grouped products haven't qty;
                 - product can have multiple prices type;
            */
            if (!qtyElement) {
                return false;
            }

            if (config.replace_qty === REPLACE_QTY.OFF) {
                return false;
            }

            var uiWidgetName = this.getUiWidgetNameByReplaceQtyValue(config.replace_qty);

            return !this.isInstancedWidget(qtyElement, uiWidgetName);
        },

        instantiatePriceWidget: function (element, config, getQtyCallback) {
            return uiWidgetHelper.instantiatePriceWidget(element, config, getQtyCallback);
        },

        isInstancedWidget: function ($element, widgetName) {
            return uiWidgetHelper.isInstanceOfWidget($element, 'puq', widgetName);
        },

        getUiWidgetNameByReplaceQtyValue: function (replaceQty) {
            return uiWidgetHelper.getUiWidgetNameByReplaceQtyValue(replaceQty);
        },

        getCurrentItemConfig: function (config, element) {
            return config;
        },

        getQtyElementOrNull: function (element, getQtyCallback) {
            return uiWidgetHelper.getQtyElementOrNull(element, getQtyCallback);
        }
    });

    return jQuery.puq.quantitiesBase;
});
