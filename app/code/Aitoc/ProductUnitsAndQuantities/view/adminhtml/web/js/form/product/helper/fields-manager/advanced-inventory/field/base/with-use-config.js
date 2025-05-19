/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    "jquery",
    "uiRegistry",
    "Aitoc_ProductUnitsAndQuantities/js/form/product/helper/fields-manager/advanced-inventory/field/base/without-use-config"
], function (
    jQuery,
    uiRegistry,
    WithoutUseConfig
) {
    return WithoutUseConfig.extend({
        useConfigComponent: null,
        exportsChecked: null,


        blockControls: function () {
            var _super = this._super;
            var self = this;
            this.onUseConfigComponent(function (useConfigComponent) {
                useConfigComponent.disabled(true);

                if (useConfigComponent.exports.checked) {
                    delete useConfigComponent.exports.checked;
                }

                _super.call(self);
            });
        },

        getBlockingInfoComponentName: function () {
            return this.getUseConfigComponentFullName();
        },

        getUseConfigComponentFullName: function () {
            var baseName = this.getComponentBasePath();
            var indexName = this.getMainComponentIndexName();
            var useConfigComponentIndexName = this.getUseConfigComponentIndexName(indexName);

            var ret = baseName;

            if (this.haveContainerComponent()) {
                ret += '.' + this.getContainerNameByIndex(indexName);
            }

            return ret += '.' + useConfigComponentIndexName;
        },

        getUseConfigComponentIndexName: function (indexName) {
            return 'use_config_' + indexName;
        },

        onGetCurrentState: function (callback) {
            var self = this;
            var state = this.state;
            var _super = this._super;

            this.onUseConfigComponent(function (useConfigComponent) {
                state.useConfigDisabled = useConfigComponent.disabled();

                if (useConfigComponent.exports.checked) {
                    state.exportsChecked = useConfigComponent.exports.checked;
                }

                _super.call(self, callback);
            });
        },

        onUseConfigComponent: function (callback) {
            var useConfigComponentFullName = this.getUseConfigComponentFullName();
            this.onComponent(useConfigComponentFullName, callback);
        },

        setCurrentState: function (state) {
            var _super = this._super;
            var self = this;

            this.onUseConfigComponent(function (useConfigComponent) {
                _super.call(self);
                useConfigComponent.disabled(state.useConfigDisabled);

                if (state.exportsChecked) {
                    useConfigComponent.exports.checked = state.exportsChecked;
                }
            });

            this._super(state);
        }
    });
});
