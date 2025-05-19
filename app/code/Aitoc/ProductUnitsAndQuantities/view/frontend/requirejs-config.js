/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

var config = {
    map: {
        '*': {
            'quantities': 'Aitoc_ProductUnitsAndQuantities/js/ui/quantities/quantities',
            'Magento_Checkout/template/minicart/item/default': 'Aitoc_ProductUnitsAndQuantities/template/Checkout/minicart/item/default'
        }
    },
    config: {
        mixins: {
            'validation/rules': {
                'Aitoc_ProductUnitsAndQuantities/js/validation/rules-mixin': true
            },
            'mage/validation': {
                'Aitoc_ProductUnitsAndQuantities/js/mage/validation-mixin-validate-item-quantity': true,
                'Aitoc_ProductUnitsAndQuantities/js/mage/validation-mixin-validate-grouped-item-quantity': true,
                'Aitoc_ProductUnitsAndQuantities/js/mage/validation-mixin-validate-by-quantities': true
            },
            'mage/validation/validation': {
                'Aitoc_ProductUnitsAndQuantities/js/mage/validation/validation-mixin': true,
                'Aitoc_ProductUnitsAndQuantities/js/Catalog/product/view/validation-mixin': true
            },
            'Magento_Catalog/product/view/validation': {
                'Aitoc_ProductUnitsAndQuantities/js/Catalog/product/view/validation-mixin': true
            },
            'Magento_Bundle/js/price-bundle': {
                'Aitoc_ProductUnitsAndQuantities/js/Bundle/price-bundle-mixin': true
            },
            'Magento_Checkout/js/sidebar': {
                'Aitoc_ProductUnitsAndQuantities/js/Checkout/sidebar-mixin': true
            },
            'Magento_Swatches/js/swatch-renderer': {
                'Aitoc_ProductUnitsAndQuantities/js/Swatches/swatch-renderer-mixin': true
            }
        }
    }
};
