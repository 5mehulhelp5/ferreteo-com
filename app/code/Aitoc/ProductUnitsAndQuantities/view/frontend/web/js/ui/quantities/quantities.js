/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    "jquery",
    "jquery/ui",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/replaceQty",
    "Aitoc_ProductUnitsAndQuantities/js/constants/replace-qty",
    "Aitoc_ProductUnitsAndQuantities/js/constants/qty-types",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/constants/block-config-mode",

    "Aitoc_ProductUnitsAndQuantities/js/quantities/mode-helper/grouped_view",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/mode-helper/bundle",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/mode-helper/product",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/mode-helper/wishlist",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/mode-helper/cart"

], function (
    $,
    ui,
    replaceQty,
    REPLACE_QTY,
    QTY_TYPES,
    BLOCK_CONFIG_MODE,
    groupViewHelper,
    bundleHelper,
    productHelper,
    wishlistHelper,
    cartHelper
) {
    "use strict";

    $.widget('puq.quantities', {
        options: {
            replace_qty: REPLACE_QTY.OFF,
            use_quantities: false,
            qty_type: QTY_TYPES.DYNAMIC,
            start_qty: 1,
            qty_increment: 1,
            end_qty: 0
        },

        _create: function () {
            var options = this.options;
            var element = this.element;

            if (options.replace_qty === REPLACE_QTY.OFF) {
                return;
            }

            var self = this;
            $(document).ready(function () {
                self.replaceQty(options, element);
            });
        },

        replaceQty: function (config, element) {
            var viewModeHelper = this.getViewModeHelper(config.mode);

            viewModeHelper.replaceQty(config, element);
        },

        getViewModeHelper: function (mode) {
            switch (mode) {
                case BLOCK_CONFIG_MODE.bundle:
                    return bundleHelper;
                case BLOCK_CONFIG_MODE.product:
                    return productHelper;
                case BLOCK_CONFIG_MODE.cart:
                    return cartHelper;
                case BLOCK_CONFIG_MODE.grouped_view:
                    return groupViewHelper;
                case BLOCK_CONFIG_MODE.wishlist:
                    return wishlistHelper;
                default:
                    throw new Error('Invalid mode' + mode);
                // todo: check grid mode
            }
        }
    });

    return $.puq.quantities;
});
