/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    "jquery",
    "jquery/ui",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/constants/block-config-mode",
    "Aitoc_ProductUnitsAndQuantities/js/constants/config-keys",
    "Aitoc_ProductUnitsAndQuantities/js/constants/qty-types",
    "Aitoc_ProductUnitsAndQuantities/js/constants/replace-qty",

    "Aitoc_ProductUnitsAndQuantities/js/quantities/mode-helper/grouped_view",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/mode-helper/bundle",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/mode-helper/product",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/mode-helper/wishlist",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/mode-helper/cart"
], function (
    jQuery,
    jQueryUi,
    BLOCK_CONFIG_MODE,
    CONFIG_KEYS,
    QTY_TYPES,
    REPLACE_QTY,
    groupViewHelper,
    bundleHelper,
    productHelper,
    wishlistHelper,
    cartHelper
) {
    return function replaceQty(config, element)
    {
        var viewModeHelper = getViewModeHelper(config.mode);

        viewModeHelper.replaceQty(config, element);
    };

    function getViewModeHelper(mode)
    {
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
