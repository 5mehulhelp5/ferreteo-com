/**
 * @category   Webkul
 * @package    Webkul_MpSellerBadge
 * @author     Webkul Software Private Limited
 * @copyright  Copyright (c)  Webkul Software Private Limited (https://webkul.com)
 * @license    https://store.webkul.com/license.html
 */
/*jshint jquery:true*/
define([
    "jquery",
    "Magento_Ui/js/modal/modal"
], function ($, modal) {
    'use strict';
        $.widget('mage.badgeDesc', {
            options: {},
            _create: function () {
                var self = this;
                var option = {
                    type: 'popup',
                    responsive: true,
                    innerScroll: true,
                    title: "Badge Description",
                    buttons: [{
                        text: $.mage.__('Close'),
                        class: 'button',
                        click: function () {
                            this.closeModal();
                        }
                    }]
                };
                $('.badge').on('click', function (event) {
                    var bid = $(this).attr('bid');
                    var popupdata = $('<div />').append($('#custom-model-popup').html('<dl>'+self.options[bid]['desc']+'</dl>'));
                    option.title = self.options[bid]['name'];
                    modal(option, popupdata);
                    popupdata.modal('openModal');
                });
            }
        });
    return $.mage.badgeDesc;
});