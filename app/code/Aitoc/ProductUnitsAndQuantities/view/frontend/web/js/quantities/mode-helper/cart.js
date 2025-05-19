/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define(
    [
        'jquery',
        "jquery/ui",
        "Aitoc_ProductUnitsAndQuantities/js/quantities/constants/block-config-mode",
        "Aitoc_ProductUnitsAndQuantities/js/quantities/mode-helper/base-not-linked"
    ],
    function (jQuery, jQueryUi, BLOCK_CONFIG_MODE) {
        jQuery.widget('puq.quantitiesCart', jQuery.puq.quantitiesBaseNotLinked, {

            getType: function () {
                return BLOCK_CONFIG_MODE.cart;
            },
            getQtyElement: function (element, config) {
                return element;
            }
        });

        return jQuery.puq.quantitiesCart;
    }
);
