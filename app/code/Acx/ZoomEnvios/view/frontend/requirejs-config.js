/**
 * Acx_ZoomEnvios Magento Extension
 *
 */

var config = {
    map: {
       '*': {
           'Magento_Checkout/js/model/cart/totals-processor/default': 'Acx_ZoomEnvios/js/model/cart/totals-processor/default',
           'Magento_Checkout/js/model/shipping-save-processor/default': 'Acx_ZoomEnvios/js/model/shipping-save-processor/default',
           'Magento_Checkout/js/model/shipping-save-processor/payload-extender': 'Acx_ZoomEnvios/js/model/shipping-save-processor/payload-extender',
           'Magento_Checkout/js/action/set-shipping-information': 'Acx_ZoomEnvios/js/action/set-shipping-information'
       }
    }
    ,
    config: {
    	mixins: {
            'Magento_Checkout/js/view/shipping': {
                'Acx_ZoomEnvios/js/view/plugin/shipping': true
            }
        }
    }
};
