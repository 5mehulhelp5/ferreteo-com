/**
 * @category   Webkul
 * @package    Webkul_MpSellerBadge
 * @author     Webkul Software Private Limited
 * @copyright  Copyright (c)  Webkul Software Private Limited (https://webkul.com)
 * @license    https://store.webkul.com/license.html
 */
/*jshint jquery:true*/
define([
    'jquery',
    'mage/adminhtml/grid',
    'prototype',
    'Magento_Ui/js/modal/alert'
], function ($, $mag,$p,alertBox) {
    'use strict';
    var allowedExtension=[];
    $.widget('mage.formBadgeWidget', {
        _create: function () {
            var self = this;
            allowedExtension = JSON.parse(self.options.allowedExtension);
            var warningLabel = self.options.warningLabel;
            var imgErrorMsg = self.options.imgErrorMsg;
            if ($(self.options.deleteImageSelector).length) {
                $(self.options.deleteImageSelector).remove();
            } else {
                $(self.options.badgesSelector).addClass("required-entry");
            }
            $(self.options.badgesSelector).on('change',function () {
                self.checkImageType(this,warningLabel,imgErrorMsg,allowedExtension);
            });
        },
        checkImageType: function (thisObject,warningLabel,imgErrorMsg,allowedExtension) {
            var fileName = $(thisObject).val();
            var ext = fileName.split('.').pop().toLowerCase();
            if (jQuery.inArray(ext, allowedExtensions) == -1) {
                alertBox({
                    title: warningLabel,
                    content: "<div class='wk-mpsbadge-warning-content'>"+imgErrorMsg+"</div>",
                    actions: {
                        always: function (){}
                    }
                });
                $(thisObject).val('');
            }
        }
    });
    return $.mage.formBadgeWidget;
});
