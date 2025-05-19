/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    'jquery',
    'jquery/ui',
    "Aitoc_ProductUnitsAndQuantities/js/quantities/controls/base-merged-up-down"
], function (jQuery) {
    jQuery.widget('puq.uiArrows', jQuery.puq.baseControlMergedUpDown, {

        controlContainerClass: 'aitoc-puq-control-container',
        controlClass: 'aitoc-puq-control-arrows',

        getDownControlHtml: function () {
            return '<div class="aitoc-puq-control-handler aitoc-puq-control-handler-down"></div>';
        },

        getUpControlHtml: function () {
            return '<div class="aitoc-puq-control-handler aitoc-puq-control-handler-up"></div>';
        }
    });

    return jQuery.puq.uiArrows;
});
