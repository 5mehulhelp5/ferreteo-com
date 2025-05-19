/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

var config = {
    config: {
        mixins: {
            'mage/validation': {
                'Aitoc_ProductUnitsAndQuantities/js/mage/validation-comma-separated-floats-mixin': true,
            },
            'Magento_CatalogInventory/js/components/use-config-settings': {
                'Aitoc_ProductUnitsAndQuantities/js/CatalogInventory/components/observable-notice-mixin': true
            },
            'Magento_CatalogInventory/js/components/use-config-min-sale-qty': {
                'Aitoc_ProductUnitsAndQuantities/js/CatalogInventory/components/observable-notice-mixin': true
            },
            'Magento_Ui/js/form/element/select': {
                'Aitoc_ProductUnitsAndQuantities/js/CatalogInventory/components/observable-notice-mixin': true
            },
            'Magento_Ui/js/lib/validation/rules': {
                'Aitoc_ProductUnitsAndQuantities/js/validation/units/allow-units-rules-mixin': true,
                'Aitoc_ProductUnitsAndQuantities/js/validation/units/price-per-divider-rules-mixin': true,
                'Aitoc_ProductUnitsAndQuantities/js/validation/units/price-per-rules-mixin': true,
                'Aitoc_ProductUnitsAndQuantities/js/validation/comma-separated-integers-rules-mixin': true,
                'Aitoc_ProductUnitsAndQuantities/js/validation/comma-separated-floats-rules-mixin': true,
                'Aitoc_ProductUnitsAndQuantities/js/validation/qty-max-steps-count-mixin': true
            }
        }
    }
};
