/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_MpVendorRegistration
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
/*jshint jquery:true*/
define([
    "jquery",
    "mage/mage",
    "mage/calendar"
], function ($, $t) {
    'use strict';
    $.widget('mage.editProfile', {
        options: {},
        _create: function () {
            var self = this;
            $('.fieldset.info').append($('.custom-fieldset'));
            $.each($(self.options.dateField), function (i, v) {
                $(this).calendar({
                    dateFormat: self.options.dateFormat,
                    changeYear: true,
                    changeMonth: true,
                    showOn: "both",
                    buttonText: "",
                });
            });

            $(self.options.imageField).change(function () {
                var ext_arr = $(this).attr("data-allowed").split(",");
                var new_ext_arr = [];
                for (var i = 0; i < ext_arr.length; i++) {
                    new_ext_arr.push(ext_arr[i]);
                    new_ext_arr.push(ext_arr[i].toUpperCase());
                }
                if (new_ext_arr.indexOf($(this).val().split("\\").pop().split(".").pop()) < 0) {
                    var self = $(this);
                    self.val('');
                    $('<div />').html('Invalid Extension. Allowed extensions are ' + $(this).attr("data-allowed"))
                        .modal({
                            title: 'Attention!',
                            autoOpen: true,
                            buttons: [{
                                text: 'Ok',
                                attr: {
                                    'data-action': 'cancel'
                                },
                                'class': 'action',
                                click: function () {
                                    self.val('');
                                    this.closeModal();
                                }
                            }]
                        });
                }
            });
        }
    });
    return $.mage.editProfile;
});
