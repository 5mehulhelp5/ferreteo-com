/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    'Magento_Ui/js/form/element/abstract',
    'Aitoc_ProductUnitsAndQuantities/js/form/product/helper/puq-section-helper',
    "Aitoc_ProductUnitsAndQuantities/js/constants/config-keys"
], function (Abstract, puqSectionHelper, CONFIG_KEYS) {
    return Abstract.extend({
        defaults: {
            valueUpdate: 'input'
        },

        onAllowUnitsParamsChanged: function () {
            this.updateValidators();
            this.validate();
        }
    });
});
