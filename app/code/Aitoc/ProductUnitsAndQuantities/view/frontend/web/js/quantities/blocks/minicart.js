/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    'jquery',
    "Aitoc_ProductUnitsAndQuantities/js/quantities/ui-widget-helper",
    "Aitoc_ProductUnitsAndQuantities/js/constants/replace-qty"
], function (jQuery, uiWidgetHelper, REPLACE_QTY) {
    return function (config, qtyElement) {
        var $qtyElement = jQuery(qtyElement);
        var puqConfig = $qtyElement.data('cart-item-puq-config');

        if (puqConfig.replace_qty !== REPLACE_QTY.OFF) {
            uiWidgetHelper.instantiateQtyWidget($qtyElement, puqConfig)
        }
    }
});
