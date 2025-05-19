/*
 * Copyright В© 2019 Aitoc. All rights reserved.
 */

define(
    [
        'jquery', "jquery/ui",
        "Aitoc_ProductUnitsAndQuantities/js/constants/config-keys",
        "Aitoc_ProductUnitsAndQuantities/js/quantities/constants/block-config-mode",
        "Aitoc_ProductUnitsAndQuantities/js/quantities/mode-helper/base-linked"
    ],
    function (jQuery, jqueryUi, CONFIG_KEYS, BLOCK_CONFIG_MODE) {

        jQuery.widget('puq.quantitiesGroupedView', jQuery.puq.quantitiesBaseLinked, {
            replaceQty: function (config, element) {
                if (!this.isLinkedProductElement(element, config)) {
                    return;
                }

                this.instantiateLinkedElementIfRequired(element, config);
            },
            getType: function () {
                return BLOCK_CONFIG_MODE.grouped_view;
            },

            isLinkedProductElement: function (element, config) {
                return jQuery(element).closest('.table.data.grouped').length === 1;
            },

            isParentProductElement: function (element, config) {
                return false;
            },

            getLinkedQtyElement: function (element) {
                return jQuery(element).parents('tbody').find('input.qty');
            },

            getParentQtyElement: function (element) {
                return jQuery(element).parents('.product-item-info').find('input.qty');
            },

            getLinkedConfigKeyByElement: function (element) {
                var $element = jQuery(element);

                var $dataContainer = $element.closest('[id^="product-price"]');
                var dataContainerId = $dataContainer.attr('id');
                if (dataContainerId) {
                    var prefixLength = 'product-price-'.length;
                    return dataContainerId.substring(prefixLength);
                }
                return $element.closest(".col.price").find('.price-box').attr("data-product-id");

            },

            getLinkedConfigByKey: function (productId, parentConfig) {
                var linkedConfig = this._super(productId, parentConfig);
                linkedConfig.allow_zero = true;

                return linkedConfig;
            }
        });

        return jQuery.puq.quantitiesGroupedView;
    }
);