/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    'jquery',
    "jquery/ui",
    "Aitoc_ProductUnitsAndQuantities/js/constants/config-keys",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/constants/bundle-product-option-types",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/constants/block-config-mode",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/mode-helper/qty-selectors/bundle-child",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/grouped-product-helper",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/mode-helper/base-linked",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/bundle-product-helper"
], function (
    jQuery,
    jqueryUi,
    CONFIG_KEYS,
    BUNDLE_PRODUCT_OPTION_TYPES,
    BLOCK_CONFIG_MODE,
    bundleChildSelector,
    groupedProductHelper,
    bundleProductHelper
) {

    jQuery.widget('puq.quantitiesConfigurableView', jQuery.puq.quantitiesBaseLinked, {

        replaceQty: function (config, element) {
            if (this.isParentProductElement(element, config)) {
                this.instantiateParentElementIfRequired(element, config);
            }
            // Child products not require instantiation. It happens in swatch-renderer-mixin.js.
        },

        getType: function () {
            return BLOCK_CONFIG_MODE.configurable;
        },

        isParentProductElement: function (element, config) {
            return jQuery(element).closest('.product-info-price').length === 1;
        },

        getParentQtyElement: function () {
            return jQuery('#qty');
        }
    });

    return jQuery.puq.quantitiesConfigurableView;
})
;
