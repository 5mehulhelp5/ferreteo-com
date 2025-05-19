/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category  Mageplaza
 * @package   Mageplaza_ProductFinder
 * @copyright Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license   https://www.mageplaza.com/LICENSE.txt
 */
define(
    [
        'jquery',
        'mage/translate',
        'Magento_Ui/js/modal/confirm',
        'Magento_Ui/js/modal/alert',
        'Magento_Ui/js/modal/modal',
    ], function ($, $t, confirm, alert, modal) {
        "use strict";

        $.widget(
            'mageplaza.actions',
            {
                _create: function () {
                    var self = this;

                    this.addPromoted(self);
                    this.deleteAllPromoted(self);
                    this.importPromoted();
                    this.deleteAllProducts(self);
                    this.generateProduct(self);
                    this.addProduct(self);
                    this.importProduct();
                },

                addPromoted: function (self) {
                    $('#mppf_add_product').click(function () {
                        var sku = $('#mpproductfinder_sku_product').val();

                        $.ajax({
                            method: 'POST',
                            url: self.options.addUrl,
                            data: {
                                formKey: window.FORM_KEY,
                                sku: sku
                            },
                            showLoader: true
                        }).success(function (response) {
                            if (response.success === false) {
                                alert({
                                    title: $t('The SKU is invalid or already exist!')
                                });
                            } else {
                                $('#mpproductfinder_promoted_grid').html(response.html);
                            }
                        });
                    });
                },

                deleteAllPromoted: function (self) {
                    $('#mppf_delete_all_promoted_product').click(function () {
                        confirm({
                            title: $t('Delete All Promoted Products'),
                            content: $t('Are you sure?'),
                            actions: {
                                confirm: function () {
                                    $.ajax({
                                        method: 'POST',
                                        url: self.options.deleteAllUrl,
                                        data: {
                                            formKey: window.FORM_KEY
                                        },
                                        showLoader: true
                                    }).success(function (response) {
                                        $('#mpproductfinder_promoted_grid').html(response.html);
                                        if (response.error) {
                                            alert({
                                                title: response.error
                                            });
                                        } else {
                                            alert({
                                                title: response.msg
                                            });
                                        }
                                    });
                                }
                            }
                        });
                    });
                },

                importPromoted: function () {
                    $('#mppf_import_promoted_product').click(function () {
                        var grid = $('#mppf-import-promoted');

                        var options = {
                            type: 'popup',
                            responsive: true,
                            innerScroll: true,
                            title: $t('Import Promoted Products'),
                            modalClass: 'import-promoted-product-modal',
                            buttons: [{
                                text: $t('Import'),
                                class: 'action wrapper primary',
                                click: function () {
                                    var form   = $('#import_promoted_form'),
                                        action = form.attr("action"),
                                        data   = new FormData(form[0]);

                                    if (form.valid()) {
                                        $.ajax({
                                            url: action,
                                            type: 'POST',
                                            data: data,
                                            mimeType: "multipart/form-data",
                                            showLoader: true,
                                            contentType: false,
                                            processData: false,
                                            cache: false,
                                            success: function (response) {
                                                var res = JSON.parse(response);

                                                $('#mpproductfinder_promoted_grid').html(res.html);
                                                grid.modal('closeModal');
                                                if (res.success === false) {
                                                    alert({
                                                        title: $t('Something went wrong, please check the file again!')
                                                    });
                                                }
                                            }
                                        });
                                    }
                                }
                            }]
                        };

                        modal(options, grid);
                        grid.modal('openModal');

                        $(document).on('click', '.action-close', function () {
                            $('#import_promoted_form').find('label.error').remove();
                        });
                    });
                },

                deleteAllProducts: function (self) {
                    $('#mppf-delete-all-product').click(function () {
                        confirm({
                            title: $t('Delete All Products'),
                            content: $t('Are you sure?'),
                            actions: {
                                confirm: function () {
                                    $.ajax({
                                        method: 'POST',
                                        url: self.options.deleteAllProductUrl,
                                        data: {
                                            formKey: window.FORM_KEY
                                        },
                                        showLoader: true
                                    }).success(function (response) {
                                        $('#mpproductfinder_products_grid').html(response.html);
                                        if (response.error) {
                                            alert({
                                                title: response.error
                                            });
                                        } else {
                                            alert({
                                                title: response.msg
                                            });
                                        }
                                    });
                                }
                            }
                        });
                    });
                },

                generateProduct: function (self) {
                    $('#mppf-generate-product').click(function () {
                        $.ajax({
                            method: 'POST',
                            url: self.options.generateProductUrl,
                            data: {
                                formKey: window.FORM_KEY
                            },
                            showLoader: true
                        }).success(function (response) {
                            $('#mpproductfinder_products_grid').html(response);
                            alert({
                                title: $t('All the products has been generated!')
                            });
                        }).error(function () {
                            alert({
                                title: $t('Something went wrong, please check again')
                            });
                        });
                    });
                },

                addProduct: function (self) {
                    $('button#mppf-add-product').click(function () {
                        var popup   = $('#mppf-add-product-modal');
                        var options = {
                            type: 'popup',
                            responsive: true,
                            innerScroll: true,
                            title: $t('Add Products'),
                            modalClass: 'add-product-modal',
                            buttons: [{
                                text: $t('Save'),
                                class: 'action wrapper primary',
                                click: function () {
                                    var select = $('select.mppf-select-filter-option-modal'),
                                        sku    = $('input#product_sku').val();
                                    var arr    = [];

                                    select.each(function () {
                                        arr.push({filter_id: $(this).attr('id'), option_id: $(this).val()});
                                    });
                                    $.ajax({
                                        method: 'POST',
                                        url: self.options.addProductUrl,
                                        data: {
                                            formKey: window.FORM_KEY,
                                            sku: sku,
                                            data: arr
                                        },
                                        showLoader: true
                                    }).success(function (response) {
                                        $('#mpproductfinder_products_grid').html(response.html);
                                        popup.trigger('closeModal');
                                        if (response.success) {
                                            alert({
                                                title: $t(response.message)
                                            });
                                        }
                                    }).error(function () {
                                        alert({
                                            title: $t('Something went wrong, please check again')
                                        });
                                    });
                                }
                            }]
                        };

                        modal(options, popup);
                        popup.modal('openModal');
                    });
                },

                importProduct: function () {
                    $('#mppf-import-product').click(function () {
                        var popup = $('#mppf-import-product-modal');

                        var options = {
                            type: 'popup',
                            responsive: true,
                            innerScroll: true,
                            title: $t('Import Products'),
                            modalClass: 'import-product-modal',
                            buttons: [{
                                text: $t('Import'),
                                class: 'action wrapper primary',
                                click: function () {
                                    var form   = $('#import_product_form'),
                                        action = form.attr("action"),
                                        data   = new FormData(form[0]);

                                    if (form.valid()) {
                                        $.ajax({
                                            url: action,
                                            type: 'POST',
                                            data: data,
                                            mimeType: "multipart/form-data",
                                            showLoader: true,
                                            contentType: false,
                                            processData: false,
                                            cache: false,
                                            success: function (response) {
                                                var res = JSON.parse(response);

                                                $('#mpproductfinder_products_grid').html(res.html);
                                                popup.modal('closeModal');
                                                if (!res.success) {
                                                    alert({
                                                        title: $t(res.message)
                                                    });
                                                }
                                            }
                                        });
                                    }
                                }
                            }]
                        };

                        modal(options, popup);
                        popup.modal('openModal');

                        $(document).on('click', '.action-close', function () {
                            $('#import_product_form').find('label.error').remove();
                        });
                    });

                }
            }
        );

        return $.mageplaza.actions;
    }
);
