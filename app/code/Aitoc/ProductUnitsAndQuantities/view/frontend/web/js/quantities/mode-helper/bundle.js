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

    jQuery.widget('puq.quantitiesGroupedView', jQuery.puq.quantitiesBaseLinked, {
        replaceQty: function (config, element) {
            if (this.isParentProductElement(element, config)) {
                this.instantiateParentElementIfRequired(element, config);
            }
            //child will иу applied by option change handler;
        },
        getType: function () {
            return BLOCK_CONFIG_MODE.bundle;
        },

        isLinkedProductElement: function (element) {
            return jQuery(element).closest('#product-options-wrapper').length === 1;
        },

        instantiateLinkedElementIfRequired: function (element, parentConfig) {
            if (!this.isInstantiateLinkedElementRequired(element)) {
                return;
            }

            return this._super(element, parentConfig);
        },

        isInstantiateLinkedElementRequired: function (element) {
            if (!this.isBelongsToQuantifiedFieldGroup(element)) {
                return false;
            }

            return this.isInstantiateRequiredForType(element);
        },

        isBelongsToQuantifiedFieldGroup: function (element) {
            var getQtyCallback = this.getMethodAsCallback(this.getLinkedQtyElement);

            return this.getQtyElementOrNull(element, getQtyCallback) !== null;
        },

        isInstantiateRequiredForType: function (element) {
            var optionType = this.getElementOptionType(element);

            return this.isOptionTypeHasQtyElement(optionType);
        },

        //todo: check if used and why only one type. Seems like error.
        isOptionTypeHasQtyElement: function (optionType) {
            var applicableOptionTypes = [
                BUNDLE_PRODUCT_OPTION_TYPES.SELECT_ONE
            ];

            return jQuery.inArray(optionType, applicableOptionTypes) === 0;
        },

        getElementOptionType: function ($element) {
            bundleProductHelper.getElementOptionType($element);
        },

        isParentProductElement: function (element, config) {
            return jQuery(element).closest('.product-info-price').length === 1;
        },

        getParentQtyElement: function (element) {
            return jQuery('#qty');
        },

        getLinkedQtyElement: function (element) {

            return bundleChildSelector(element);
        },

        getLinkedConfigKeyByElement: function (element) {
            return this.getSelectionIdByElement(element);
        },

        getSelectionIdByElement: function (element) {
            return groupedProductHelper.getSelectionIdByElement(element);
        }
    });

    return jQuery.puq.quantitiesGroupedView;
})
;
