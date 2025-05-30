/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/*browser:true*/
/*global define*/
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'checkmo',
                component: 'Magento_OfflinePayments/js/view/payment/method-renderer/checkmo-method'
            },
            {
                type: 'banktransfer',
                component: 'Ibnab_BankInfo/js/view/payment/method-renderer/banktransfer-method'
            },
            {
                type: 'cashondelivery',
                component: 'Magento_OfflinePayments/js/view/payment/method-renderer/cashondelivery-method'
            },
            {
                type: 'purchaseorder',
                component: 'Magento_OfflinePayments/js/view/payment/method-renderer/purchaseorder-method'
            }
        );
        /** Add view logic here if needed */
        return Component.extend({});
    }
);
