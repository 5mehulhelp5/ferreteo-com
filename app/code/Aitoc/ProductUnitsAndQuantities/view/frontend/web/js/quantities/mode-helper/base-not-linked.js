/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define(
    ["jquery", "jquery/ui", "Aitoc_ProductUnitsAndQuantities/js/quantities/mode-helper/base"],
    function (jQuery) {
        "use strict";

        jQuery.widget('puq.quantitiesBaseNotLinked', jQuery.puq.quantitiesBase, {
            replaceQty: function (config, element) {
                var getQtyCallback = this.getMethodAsCallback(this.getQtyElement);

                return this.instantiateWidgetIfRequired(config, element, getQtyCallback);
            },

            getQtyElement: function (element, config) {
                this.throwNowImplemented();
            }
        });

        return jQuery.puq.quantitiesBaseNotLinked;
    }
);
