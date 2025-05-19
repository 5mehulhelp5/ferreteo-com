/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/shipping-save-processor'
], function (quote, shippingSaveProcessor) {
    'use strict';

    return function () {
        quote.shippingAddress().customAttributes = {
            pickup_office: jQuery('#pickup-office').val()
        }
        return shippingSaveProcessor.saveShippingInformation(quote.shippingAddress().getType());
    };
});
