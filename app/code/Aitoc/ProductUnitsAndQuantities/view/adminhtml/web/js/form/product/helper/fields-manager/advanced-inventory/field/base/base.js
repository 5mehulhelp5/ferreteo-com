/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    'jquery',
    'mage/translate',
    'uiClass',
    'uiRegistry',
    "Aitoc_ProductUnitsAndQuantities/js/constants/messages"
], function (jQuery, mageTranslate, uiClass, uiRegistry, MESSAGES) {
    return uiClass.extend({
        defaults: {
            state: {},
            stateBackup: null,
            blockInfoMessage: null
        },

        onMainComponent: function (callback) {
            var mainComponentFullName = this.getMainComponentFullName();
            this.onComponent(mainComponentFullName, callback);
        },

        onComponent: function (componentName, callback) {
            uiRegistry.get(componentName, callback);
        },

        getMainComponentFullName: function () {
            var baseName = this.getComponentBasePath();
            var mainComponentIndexName = this.getMainComponentIndexName();

            var ret = baseName;

            if (this.haveContainerComponent()) {
                ret += '.' + this.getContainerNameByIndex(mainComponentIndexName);
            }

            return ret += '.' + mainComponentIndexName;
        },

        haveContainerComponent: function () {
            return this.haveContainer;
        },

        getContainerNameByIndex: function (indexName) {
            return 'container_' + indexName;
        },

        getComponentBasePath: function () {
            return this.basePath;
        },

        getMainComponentIndexName: function () {
            return this.mainKey;
        },

        block: function () {
            var self = this;
            this.onSaveCurrentState(function () {
                self.blockControls();
                self.addBlockingInfo();
            });
        },

        unblock: function () {
            this.removeBlockingInfo();
            this.restoreState();
        },

        onSaveCurrentState: function (callback) {
            var self = this;
            this.onGetCurrentState(function (state) {
                self.stateBackup = state;

                callback();
            });
        },

        restoreState: function () {
            this.setCurrentState(this.stateBackup);
        },

        getBlockingInfoMessage: function () {
            if (!this.blockInfoMessage) {
                this.blockInfoMessage = jQuery.mage.__(MESSAGES.PRODUCT_MIN_MAX_QTY_NOTICE);
            }

            return this.blockInfoMessage;
        },

        addBlockingInfo: function () {
            var message = this.getBlockingInfoMessage();

            this.onBlockingInfoComponent(function (blockingInfoComponent) {
                blockingInfoComponent.notice(message);
            });
        },

        removeBlockingInfo: function () {
            this.onBlockingInfoComponent(function (blockingInfoComponent) {
                blockingInfoComponent.notice('');
            });
        },

        onBlockingInfoComponent: function (callback) {
            var componentName = this.getBlockingInfoComponentName();
            this.onComponent(componentName, callback)
        }
    });
});
