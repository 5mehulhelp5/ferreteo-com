/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define(
    [
        'jquery',
        'jquery/ui',
        "Aitoc_ProductUnitsAndQuantities/js/quantities/qty-control-applier",
        'Aitoc_ProductUnitsAndQuantities/js/quantities/current-product-helper'
    ],
    function ($, jQueryUi, qtyControlApplier, currentProductHelper) {
        'use strict';

        return function (original) {
            if (!original) {
                return original;
            }
            $.widget('mage.SwatchRenderer', $.mage.SwatchRenderer, {
                _OnClick: function ($this, $widget) {
                    var ret = this._super($this, $widget);
                    this.updateQtyControlIfRequired();

                    return ret;
                },
                updateQtyControlIfRequired: function () {
                    if (!this.isUpdateQtyControlRequired()) {
                        return;
                    }

                    var productId = this.getProduct();
                    this.applyCustomControlIfRequired(productId);
                },
                isUpdateQtyControlRequired: function () {
                    var productId = this.getProduct();

                    if (!productId) {
                        return false;
                    }

                    //Product config exists only on product page, but switcher could be used on other pages (for example category page).
                    return currentProductHelper.isConfigExists();
                },
                applyCustomControlIfRequired: function (productId) {
                    var customControlConfig = this.getCustomControlConfig(productId);
                    var qtyElement = this.getQtyElement();
                    var optionValue = parseFloat(qtyElement.val()); //currently (v2.2.4) not updated according to min/max/qty for selected option
                    //todo: check in next releases

                    qtyControlApplier.applyCustomControlIfRequired(customControlConfig, qtyElement, optionValue);
                },
                getCustomControlConfig: function (productId) {
                    return currentProductHelper.getLinkedProductConfig(productId);
                },
                getQtyElement: function () {
                    return $('#qty');
                },
                getOptionValue: function (qtyElement, customControlConfig) {
                    var srcValue = parseFloat(qtyElement.val());
                }
            });

            return $.mage.SwatchRenderer;
        }
    }
);
