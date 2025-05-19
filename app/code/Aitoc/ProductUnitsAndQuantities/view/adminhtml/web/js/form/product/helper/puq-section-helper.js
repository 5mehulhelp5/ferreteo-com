/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define(['jquery', 'uiRegistry'], function (jQuery, uiRegistry) {
    var useConfigPrefix = 'use_config_';

    return {
        onFieldByIndex: function (fieldIndex, callback) {
            uiRegistry.get(this.getFieldSelectorByIndex(fieldIndex), callback);
        },

        getFieldByIndex: function (fieldIndex) {
            return uiRegistry.get(this.getFieldSelectorByIndex(fieldIndex));
        },

        getUseConfigFieldByIndex: function (fieldIndex) {
            var useConfigFieldIndex = this.getUseConfigFieldIndex(fieldIndex);

            return uiRegistry.get(this.getFieldSelectorByIndex(useConfigFieldIndex));
        },

        getUseConfigFieldIndex: function (fieldIndex) {
            return useConfigPrefix + fieldIndex;
        },

        onFieldsByIndexes: function (fieldIndexes, callback) {
            var fullFieldNames = this.getFullFieldNamesByIndexes(fieldIndexes);

            uiRegistry.get(fullFieldNames, callback);
        },

        getFullFieldNamesByIndexes: function (fieldIndexes) {
            var fullFieldNames = [];
            var self = this;

            jQuery.each(fieldIndexes, function (key, fieldIndex) {
                fullFieldNames.push(self.getFieldSelectorByIndex(fieldIndex));
            });

            return fullFieldNames;
        },

        getFieldSelectorByIndex: function (fieldIndex) {
            return this.isUseConfigFieldIndex(fieldIndex)
                ? this.getUseConfigFieldSelector(fieldIndex)
                : this.getNotUseConfigFieldSelector(fieldIndex);
        },

        isUseConfigFieldIndex: function (fieldIndex) {
            return fieldIndex.startsWith(useConfigPrefix);
        },

        getUseConfigFieldSelector: function (useConfigFieldIndex) {
            var fieldIndex = useConfigFieldIndex.substring(useConfigPrefix.length);

            return this.getFieldFullName(fieldIndex, useConfigFieldIndex);
        },

        getNotUseConfigFieldSelector: function (fieldIndex) {
            return this.getFieldFullName(fieldIndex, fieldIndex);
        },

        getFieldFullName: function (containerIndex, fieldIndex) {
            return 'product_form.product_form.product-units-and-quantities.container_' + containerIndex +'.' + fieldIndex;
        }
    }
});
