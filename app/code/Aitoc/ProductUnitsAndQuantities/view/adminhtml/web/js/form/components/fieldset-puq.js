/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    'jquery',
    'Magento_Ui/js/form/components/fieldset',
    'uiRegistry',
    'Aitoc_ProductUnitsAndQuantities/js/form/product/helper/fields-manager/advanced-inventory-helper',
    'Aitoc_ProductUnitsAndQuantities/js/form/product/helper/puq-section-helper',
    'Aitoc_ProductUnitsAndQuantities/js/constants/product-types',
    'Aitoc_ProductUnitsAndQuantities/js/constants/replace-qty',
    "Aitoc_ProductUnitsAndQuantities/js/constants/config-keys",
    "Aitoc_ProductUnitsAndQuantities/js/constants/catalog-inventory-keys",
    "Aitoc_ProductUnitsAndQuantities/js/constants/qty-types"
], function (
    jQuery,
    Fieldset,
    uiRegistry,
    advancedInventoryHelper,
    puqSectionHelper,
    PRODUCT_TYPES,
    REPLACE_QTY,
    CONFIG_KEYS,
    CATALOG_INVENTORY_KEYS,
    QTY_TYPES
) {
    return Fieldset.extend({
        productTypeId: null,

        initialize: function () {
            this._super();

            var productType = this.getProductType();
            this.fixReplaceQtyOptionsByProductType(productType);
            this.setFieldsDependencies(productType);
        },

        getProductType: function () {
            var productDataSource = this.getProductDataSource();

            return productDataSource.data.product.stock_data.type_id;
        },

        getProductDataSource: function () {
            return uiRegistry.get('product_form.product_form_data_source');
        },

        fixReplaceQtyOptionsByProductType: function (productType) {
            var self = this;

            this.onReplaceQtyControlInitialized(
                function (replaceQtyComponent) {
                    self.isOnOffProductType(productType)
                        ? self.hideReplaceQtyControlsOptions(replaceQtyComponent)
                        : self.hideReplaceQtyOptionOn(replaceQtyComponent)
                }
            );
        },

        onReplaceQtyControlInitialized: function (callback) {
            return this.onComponentInitializedByIndex('container_replace_qty.replace_qty', callback);
        },

        onComponentInContainerInitializedByIndex: function (indexName, callback) {
            var componentInContainerName = this.getComponentInContainerNameByIndex(indexName);

            this.onComponentInitializedByIndex(componentInContainerName, callback);
        },

        onComponentInitializedByIndex: function (componentIndex, callback) {
            var queryString = this.getComponentQueryStringByIndex(componentIndex);

            return uiRegistry.get(queryString, callback);
        },

        getComponentInContainerNameByIndex: function (indexName) {
            var containerName = this.getRealContainerName(indexName);

            return containerName + '.' + indexName;
        },

        onComponentContainerInitializedByIndex: function (componentIndex, callback) {
            var containerName = this.getRealContainerName(componentIndex, callback);
            this.onComponentInitializedByIndex(containerName, callback);
        },

        getComponentQueryStringByIndex: function (componentIndex) {
            return 'name = product_form.product_form.product-units-and-quantities.' + componentIndex;
        },

        isOnOffProductType: function (productTypeId) {
            var onOffProductTypes = [PRODUCT_TYPES.grouped];

            return (jQuery.inArray(productTypeId, onOffProductTypes) !== -1 )
        },

        hideReplaceQtyOptionOn: function (replaceQtyComponent) {
            replaceQtyComponent.options.remove(function (item) {
                return item.value === REPLACE_QTY.ON;
            });
        },

        hideReplaceQtyControlsOptions: function (replaceQtyComponent) {
            replaceQtyComponent.options.remove(function (item) {
                var onOffValues = [REPLACE_QTY.ON, REPLACE_QTY.OFF];
                return jQuery.inArray(item.value, onOffValues) === -1;
            });
        },

        setFieldsDependencies: function (productType) {
            if (this.hideQtyRequired(productType)) {
                this.hideQtyControls();
            }

            if (this.hideUnitsRequired(productType)) {
                this.hideUnitsControls();
            }
        },

        hideUnitsRequired: function (productType) {
            return productType === PRODUCT_TYPES.grouped;
        },

        hideQtyRequired: function (productType) {
            return this.isOnOffProductType(productType);
        },

        hideQtyControls: function () {
            var qtyContainers = [
                CONFIG_KEYS.QTY_TYPE,
                CONFIG_KEYS.USE_QUANTITIES,
                CONFIG_KEYS.START_QTY,
                CONFIG_KEYS.END_QTY,
                CONFIG_KEYS.QTY_INCREMENT,
                CONFIG_KEYS.IS_QTY_DECIMAL
            ];

            this.hideContainers(qtyContainers);
        },

        hideUnitsControls: function () {
            var unitsContainers = [
                CONFIG_KEYS.ALLOW_UNITS,
                CONFIG_KEYS.PRICE_PER,
                CONFIG_KEYS.PRICE_PER_DIVIDER
            ];

            this.hideContainers(unitsContainers);
        },

        hideContainers: function (containersNames) {
            var self = this;
            containersNames.each(function (containerName) {
                self.hideContainerComponentByName(containerName);
            });
        },

        hideControl: function (indexName) {
            this.hideComponentByName(indexName);
        },

        hideContainerComponentByName: function (name) {
            var realContainerName = this.getRealContainerName(name);
            this.hideComponentByName(realContainerName);
        },

        hideComponentByName: function (fullName) {
            this.onComponentInitializedByIndex(fullName, function (container) {
                container.visible(false);
            })
        },

        getRealContainerName: function (name) {
            return 'container_' + name;
        },

        /* state handlers */

        onReplaceQtyChanged: function (replaceQtyValue) {
            var isOnOffProduct = this.isOnOffCurrentProductType();

            if (isOnOffProduct) {
                return;
            }

            this.updateUseDecimalsComponent(replaceQtyValue);
            this.updateQtyTypeControl(replaceQtyValue);
            this.updateAdvancedInventoryModalFields(replaceQtyValue);
        },

        onProductTypeIdChanged: function (productTypeId) {
            var replaceQtyValue = this.getReplaceQtyValue();
            this.updateUseDecimalsComponent(replaceQtyValue);
        },

        isOnOffCurrentProductType: function () {
            var productType = this.getProductType();

            return this.isOnOffProductType(productType);
        },

        onQtyTypeChanged: function (qtyTypeValue) {
            var self = this;

            this.onComponentInContainerInitializedByIndex(CONFIG_KEYS.REPLACE_QTY, function (replaceQtyComponent) {
                var replaceQtyValue = replaceQtyComponent.value();

                self.updateQtyTypeChildControls(replaceQtyValue, qtyTypeValue);
            });
        },

        updateCatalogInventoryFieldDisabledByKey: function (useConfigValue, fieldIndex) {
            if (this.getReplaceQtyValue() != REPLACE_QTY.OFF) {
                return;
            }

            this.onGetStockDataFieldByIndex(fieldIndex, function (stockDataField) {
                var disabled = (useConfigValue === '1');
                stockDataField.disabled(disabled);
            });
        },

        onGetStockDataFieldByIndex: function (fieldIndex, callback) {
            var stockDataFieldName = this.getStockDataFieldFullName(fieldIndex);

            uiRegistry.get(stockDataFieldName, function (stackDataField) {
                callback(stackDataField);
            });
        },

        getStockDataFieldFullName: function (fieldIndex) {
            return 'product_form.product_form.advanced_inventory_modal.stock_data.container_' + fieldIndex + '.' + fieldIndex;
        },

        onEnableQtyIncrementsChanged: function (isQtyIncrements) {
            var replaceQtyValue = this.getReplaceQtyValue();
            var qtyTypeValue = this.getQtyTypeValue();

            this.updateQtyIncrementComponent(replaceQtyValue, qtyTypeValue, isQtyIncrements);
        },

        getReplaceQtyValue: function () {
            var productDataSource = this.getProductDataSource();

            return productDataSource.data.product.replace_qty;
        },

        getQtyTypeValue: function () {
            var productDataSource = this.getProductDataSource();

            return productDataSource.data.product.qty_type;
        },

        updateQtyTypeControl: function (replaceQtyValue) {
            var self = this;

            this.onComponentContainerInitializedByIndex(CONFIG_KEYS.QTY_TYPE, function (qtyTypeContainer) {
                var visible = (replaceQtyValue !== REPLACE_QTY.OFF);

                qtyTypeContainer.visible(visible);
            });

            this.onComponentInContainerInitializedByIndex(CONFIG_KEYS.QTY_TYPE, function (qtyTypeComponent) {
                var qtyTypeValue = qtyTypeComponent.value();

                self.updateQtyTypeChildControls(replaceQtyValue, qtyTypeValue);
            });
        },

        updateQtyTypeChildControls: function (replaceQtyValue, qtyTypeValue) {
            this.updateUseQuantitiesComponent(replaceQtyValue, qtyTypeValue);
            this.updateMinQtyComponent(replaceQtyValue, qtyTypeValue);
            this.updateMaxQtyComponent(replaceQtyValue, qtyTypeValue);
            this.updateQtyIncrementComponent(replaceQtyValue, qtyTypeValue);
        },

        updateAdvancedInventoryModalFields: function (replaceQtyValue) {
            this.isAdvancedInventoryShouldBeDisabled(replaceQtyValue)
                ? this.blockAdvancedInventoryFields()
                : this.unblockAdvancedInventoryFields();
        },

        isAdvancedInventoryShouldBeDisabled: function (replaceQtyValue) {
            return (replaceQtyValue != REPLACE_QTY.OFF);
        },

        blockAdvancedInventoryFields: function () {
            advancedInventoryHelper.blockAdvancedInventoryFields();
        },

        unblockAdvancedInventoryFields: function () {
            advancedInventoryHelper.unblockAdvancedInventoryFields();
        },

        updateUseQuantitiesComponent: function (replaceQtyValue, qtyTypeValue) {
            // var visible = ((replaceQtyValue != REPLACE_QTY.OFF) && (qtyTypeValue == QTY_TYPES.STATIC));
            var visible = ((replaceQtyValue !== REPLACE_QTY.OFF)
                && (replaceQtyValue !== REPLACE_QTY.ON)
                && (qtyTypeValue == QTY_TYPES.STATIC)
            );

            this.onComponentContainerInitializedByIndex(
                CONFIG_KEYS.USE_QUANTITIES,
                function (useQuantitiesComponent) {
                    useQuantitiesComponent.visible(visible);
                }
            );
        },

        updateMinQtyComponent: function (replaceQtyValue, qtyTypeValue) {
            var visible = ((replaceQtyValue != REPLACE_QTY.OFF) && (qtyTypeValue != QTY_TYPES.STATIC));

            this.onComponentContainerInitializedByIndex(
                CONFIG_KEYS.START_QTY,
                function (component) {
                    component.visible(visible);
                }
            );
        },

        updateMaxQtyComponent: function (replaceQtyValue, qtyTypeValue) {
            var visible = ((replaceQtyValue != REPLACE_QTY.OFF) && (qtyTypeValue != QTY_TYPES.STATIC));

            this.onComponentContainerInitializedByIndex(
                CONFIG_KEYS.END_QTY,
                function (component) {
                    component.visible(visible);
                }
            );
        },

        updateQtyIncrementComponent: function (replaceQtyValue, qtyTypeValue) {
            var visible = ((replaceQtyValue != REPLACE_QTY.OFF) && (qtyTypeValue != QTY_TYPES.STATIC));

            this.onComponentContainerInitializedByIndex(
                CONFIG_KEYS.QTY_INCREMENT,
                function (component) {
                    component.visible(visible);
                }
            );
        },

        updateUseDecimalsComponent: function (replaceQtyValue) {
            var visible = (replaceQtyValue != REPLACE_QTY.OFF) && !this.isBundleProduct();

            this.onComponentContainerInitializedByIndex(
                CONFIG_KEYS.IS_QTY_DECIMAL,
                function (component) {
                    component.visible(visible);
                }
            );
        },

        isBundleProduct: function () {
            return this.productTypeId === PRODUCT_TYPES.bundle;
        }
    });
});

