/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    'jquery',
    'jquery/ui',
    "Aitoc_ProductUnitsAndQuantities/js/quantities/controls/base-merged-up-down"
], function (jQuery) {
    jQuery.widget('puq.uiPlusMinus', jQuery.puq.baseControlMergedUpDown, {

        controlContainerClass: 'aitoc-puq-control-container',
        controlClass: 'aitoc-puq-control-plus-minus',

        getDownControlHtml: function () {
            return '<button class="aitoc-puq-control-handler aitoc-puq-control-handler-down"> - </button>';
        },

        getUpControlHtml: function () {
            return '<button class="aitoc-puq-control-handler aitoc-puq-control-handler-up"> + </button>';
        }
    });

    return jQuery.puq.uiPlusMinus;
});
