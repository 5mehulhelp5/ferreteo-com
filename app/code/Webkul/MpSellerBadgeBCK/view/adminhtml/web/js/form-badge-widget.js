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
    'prototype'
], function ($, $mag,$p) {
    'use strict';
    var allowedExtension=[];
    $.widget('mage.formBadgeWidget', {
        _create: function () {
            var self = this;
            allowedExtension = JSON.parse(self.options.allowedExtension);
            if ($(self.options.deleteImageSelector).length) {
                $(self.options.deleteImageSelector).remove();
            } else {
                $(self.options.badgesSelector).addClass("required-entry");
            }
            $(self.options.badgesSelector).on('change',function () {
                self.checkImageType(this);
            });
        },
        checkImageType: function (thisObject) {
            var fileName = $(thisObject).val();
            var ext = fileName.split('.').pop().toLowerCase();
            if (jQuery.inArray(ext, allowedExtensions) == -1) {
                alert('Image type not allowed!');
                $(thisObject).val('');
            }
        }
    });
    return $.mage.formBadgeWidget;
});
