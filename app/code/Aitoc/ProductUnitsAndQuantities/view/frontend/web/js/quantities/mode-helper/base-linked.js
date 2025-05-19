/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define(
    ["jquery", "jquery/ui",
        "Aitoc_ProductUnitsAndQuantities/js/constants/config-keys",
        "Aitoc_ProductUnitsAndQuantities/js/quantities/mode-helper/base"
    ],
    function (jQuery, jQueryUi, CONFIG_KEYS) {
        "use strict";

        jQuery.widget('puq.quantitiesBaseLinked', jQuery.puq.quantitiesBase, {
            isParentProductElement: function (element, config) {
                this.throwNowImplemented();
            },

            instantiateParentElementIfRequired: function (element, parentConfig) {
                var getQtyCallback = this.getMethodAsCallback(this.getParentQtyElement);

                return this.instantiateWidgetIfRequired(parentConfig, element, getQtyCallback);
            },

            getParentQtyElement: function () {
                this.throwNowImplemented();
            },

            getLinkedQtyElement: function () {
                this.throwNowImplemented();
            },

            instantiateLinkedElementIfRequired: function (element, parentConfig) {
                var getQtyCallback = this.getMethodAsCallback(this.getLinkedQtyElement);
                var config = this.getLinkedConfig(parentConfig, element);

                return this.instantiateWidgetIfRequired(config, element, getQtyCallback);
            },

            getLinkedConfigByKey: function (productId, parentConfig) {
                var config = parentConfig[CONFIG_KEYS.LINKED_PRODUCTS][productId];
                config.allow_zero = false;

                return config;
            },

            getLinkedConfig: function (parentConfig, element) {
                var productId = this.getLinkedConfigKeyByElement(element);

                if (!productId) {
                    return null;
                }

                var childProductConfig = this.getLinkedConfigByKey(productId, parentConfig);
                childProductConfig.mode = this.getType();

                return childProductConfig;
            }
        });

        return jQuery.puq.quantitiesBaseLinked;
    }
);
