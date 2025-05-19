/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    "jquery",
    "Aitoc_ProductUnitsAndQuantities/js/form/product/helper/fields-manager/advanced-inventory/field/min-sale-qty",
    "Aitoc_ProductUnitsAndQuantities/js/form/product/helper/fields-manager/advanced-inventory/field/max-sale-qty",
    "Aitoc_ProductUnitsAndQuantities/js/form/product/helper/fields-manager/advanced-inventory/field/is-qty-decimal",
    "Aitoc_ProductUnitsAndQuantities/js/form/product/helper/fields-manager/advanced-inventory/field/enable-qty-increments",
    "Aitoc_ProductUnitsAndQuantities/js/form/product/helper/fields-manager/advanced-inventory/field/qty-increments",
    "Aitoc_ProductUnitsAndQuantities/js/constants/config-keys",
    "Aitoc_ProductUnitsAndQuantities/js/constants/catalog-inventory-keys"
], function (
    jQuery,
    MinSaleQtyField,
    MaxSaleQtyField,
    IsQtyDecimalField,
    EnableQtyIncrementsField,
    QtyIncrementsField,
    CONFIG_KEYS,
    CATALOG_INVENTORY_KEYS
) {
    var advancedInventoryManagedFieldKeys = [
        CATALOG_INVENTORY_KEYS.MIN_SALE_QTY,
        CATALOG_INVENTORY_KEYS.MAX_SALE_QTY,
        CATALOG_INVENTORY_KEYS.IS_QTY_DECIMAL,
        CATALOG_INVENTORY_KEYS.ENABLE_QTY_INCREMENTS,
        CATALOG_INVENTORY_KEYS.QTY_INCREMENTS
    ];

    var fields = null;

    return {
        isBlocked: false,
        getFields: function () {
            if (!fields) {
                fields = this._getFields();
            }

            return fields;
        },

        _getFields: function () {
            var ret = {};

            var basePath = 'product_form.product_form.advanced_inventory_modal.stock_data';

            var commonConfig = {basePath: basePath};
            ret[CATALOG_INVENTORY_KEYS.MIN_SALE_QTY] = MinSaleQtyField(commonConfig);
            ret[CATALOG_INVENTORY_KEYS.MAX_SALE_QTY] = MaxSaleQtyField(commonConfig);
            ret[CATALOG_INVENTORY_KEYS.IS_QTY_DECIMAL] = IsQtyDecimalField(commonConfig);
            ret[CATALOG_INVENTORY_KEYS.ENABLE_QTY_INCREMENTS] = EnableQtyIncrementsField(commonConfig);
            ret[CATALOG_INVENTORY_KEYS.QTY_INCREMENTS] = QtyIncrementsField(commonConfig);

            return ret;
        },

        blockAdvancedInventoryFields: function () {
            if (this.isBlocked) {
                return
            }

            var fields = this.getFields();

            jQuery.each(fields, function (key, field) {
                field.block();
            });

            this.isBlocked = true;
        },

        unblockAdvancedInventoryFields: function () {
            if (!this.isBlocked) {
                return;
            }

            var fields = this.getFields();

            jQuery.each(fields, function (key, field) {
                field.unblock();
            });

            this.isBlocked = false;
        },

        getAdvancedInventoryComponentsNames: function () {
            var ret = [];
            var self = this;

            jQuery.each(advancedInventoryManagedFieldKeys, function (key, indexName) {
                var fullName = self.getAdvancedInventoryComponentNameByIndex(indexName);
                ret.push(fullName);
            });

            return ret;
        },

        getAdvancedInventoryComponentNameByIndex: function (indexName) {
            return 'product_form.product_form.advanced_inventory_modal.stock_data.container_' + indexName + '.' + indexName;
        }
    }
});
