/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    "jquery",
    "Aitoc_ProductUnitsAndQuantities/js/form/product/helper/fields-manager/advanced-inventory/field/base/base"
], function (
    jQuery,
    BaseField
) {
    return BaseField.extend({
        blockControls: function () {
            this.onMainComponent(function (mainComponent) {
                mainComponent.disabled(true);
            });
        },

        onGetCurrentState: function (callback) {
            var state = this.state;

            this.onMainComponent(function (mainComponent) {
                state.mainDisabled = mainComponent.disabled();
                callback(state);
            });
        },

        setCurrentState: function (state) {
            this.onMainComponent(function (mainComponent) {
                mainComponent.disabled(state.mainDisabled);
            });
        },

        getBlockingInfoComponentName: function () {
            return this.getMainComponentFullName();
        }
    });
});
