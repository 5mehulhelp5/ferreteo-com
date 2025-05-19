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
        'Magento_Ui/js/modal/modal',
        'mage/translate',
        'Magento_Ui/js/modal/confirm'
    ], function ($, modal, $t, confirm) {
        "use strict";

        var defaultTable    = $('#mppf-table-default-attributes'),
            tableContent    = defaultTable.find('#filter-table-content'),
            defaultRowClass = 'default-row-';

        $.widget(
            'mageplaza.filters',
            {
                _create: function () {
                    var self = this;

                    self.addFilterOptions(self);
                    self.settingFilterOptions(self);
                    self.deleteRow(self);
                },

                addFilterOptions: function (self) {
                    var countSelect = 0,
                        countRow    = tableContent.find('.attribute-options-row'),
                        addBtn      = defaultTable.find('#add_new_filter_button');

                    if (countRow.length) {
                        countSelect = countRow.length;
                    }

                    if (addBtn.length) {
                        addBtn.click(function () {
                            if (tableContent.length) {
                                countSelect++;
                                self.createRow(self, countSelect);
                            }
                        });
                    }
                },

                createRow: function (self, count) {
                    var ruleId      = self.options.data.ruleId,
                        mode        = self.options.data.mode,
                        sortBy      = self.options.data.sortBy,
                        displayType = self.options.data.displayType,
                        html        = '<tr class="attribute-options-row ' + defaultRowClass + count + '">';

                    html += self.createNameColumn(count);
                    html += self.createSettingColumn(mode, count, ruleId);
                    html += self.createSortByColumn(count, sortBy);
                    html += self.createDisplayTypeColumn(count, mode, displayType);
                    html += self.createActionColumn(count);
                    html += '</tr>';

                    tableContent.append(html);
                },

                createNameColumn: function (count) {
                    var html = '<td>';

                    html += '<input required type="text" name="filter[' + count + '][name]">';
                    html += '<input type="hidden" name="filter[' + count + '][attribute]">';
                    html += '</td>';

                    return html;
                },

                createSettingColumn: function (mode, count, ruleId) {
                    var html = '<td>';

                    if (mode === 'auto') {
                        html += '<p class="mppf-setting-options" data-row-number="' + count + '"></p>';
                        html += '<a class="setting-attribute-row" data-row-number="' + count + '">';
                        html += '<span>' + $t('Setting') + '</span></a>';
                        html += '</td>';
                    } else if (mode === 'manual' && ruleId && defaultTable.find('tr.attribute-options-row')) {
                        html += '<div class="admin__field-tooltip" style="cursor:pointer;">';
                        html += '<a class="admin__field-tooltip-action action-help"></a>';
                        html += '<div class="admin__field-tooltip-content">';
                        html += $t('Please save before setting this option.');
                        html += '</div></div></td>';
                    } else {
                        html = '';
                    }

                    return html;
                },

                createSortByColumn: function (count, sortBy) {
                    var html = '<td>';

                    html += '<select name="filter[' + count + '][sort_by]" required>';
                    sortBy.forEach(function (item) {
                        html += '<option value="' + item.value + '">' + item.label + '</option>';
                    });
                    html += '</select></td>';

                    return html;
                },

                createDisplayTypeColumn: function (count, mode, displayType) {
                    var html     = '<td>',
                        disabled = '';

                    if (mode === 'auto') {
                        disabled = 'disabled';
                    }

                    html += '<select name="filter[' + count + '][display]" required ' + disabled + '>';
                    displayType.forEach(function (item) {
                        html += '<option value="' + item.value + '">' + item.label + '</option>';
                    });
                    html += '</select></td>';

                    return html;
                },

                createActionColumn: function (count) {
                    var html = '<td>';

                    html += '<a class="delete-attribute-row" data-row-number="' + count + '">';
                    html += '<i class="action-delete"></i></a></td>';

                    return html;
                },

                settingFilterOptions: function (self) {
                    $(tableContent).on('click', '.setting-attribute-row', function () {
                        var del      = $(this),
                            rows     = del.parent().parent('tr.attribute-options-row'),
                            rowNum   = del.attr('data-row-number'),
                            filterId = del.attr('data-filter-id');

                        if (rows.hasClass(defaultRowClass + rowNum)) {
                            $.ajax({
                                method: 'POST',
                                url: self.options.data.ajaxUrl,
                                data: {
                                    formKey: window.FORM_KEY,
                                    filterId: filterId
                                },
                                showLoader: true
                            }).done(function (response) {
                                $('#mppf-select-attribute').html(response).trigger('contentUpdated');
                                self.initPopup(self, rowNum, filterId);
                            });
                        }
                    });
                },

                initPopup: function (self, rowNum, filterId) {
                    var grid   = $('#mppf-select-attribute'),
                        ruleId = self.options.data.ruleId;

                    var options = {
                        type: 'popup',
                        responsive: true,
                        innerScroll: true,
                        title: $t('Filter Options'),
                        modalClass: 'add-attribute-filter-popup',
                        buttons: [{
                            text: $t('Save'),
                            class: 'action wrapper primary',
                            click: function () {
                                if (self.options.data.mode === 'auto') {
                                    var checked   = $('input[name="attribute_id"]:checked'),
                                        attribute = checked.val(),
                                        attrId    = attribute ? attribute : null,
                                        selector  = 'input[name="filter[' + rowNum + '][attribute]"]',
                                        label     = checked.parent().parent().find('.col-frontend_label').text();

                                    $(selector).attr('value', attrId);
                                    $('.mppf-setting-options[data-row-number="' + rowNum + '"]').html(label);
                                    this.closeModal();
                                } else {
                                    var form   = $('#add-filter-options'),
                                        action = form.attr("action"),
                                        data   = new FormData(form[0]);

                                    if (form.valid()) {
                                        $.ajax({
                                            url: action + 'filter_id/' + filterId + '/rule_id/' + ruleId,
                                            type: 'POST',
                                            data: data,
                                            mimeType: "multipart/form-data",
                                            showLoader: true,
                                            contentType: false,
                                            processData: false,
                                            cache: false,
                                            success: function (response) {
                                                var res = JSON.parse(response);

                                                if (res.success) {
                                                    $('#mppf-add-product-modal').html(res.add_product_popup);
                                                }
                                                grid.modal('closeModal');
                                            }
                                        });
                                    }
                                }
                            }
                        }]
                    };

                    modal(options, grid);
                    grid.modal('openModal');
                },

                deleteRow: function (self) {
                    $(document).on('click', 'a.delete-attribute-row', function () {
                        var del      = $(this),
                            rows     = del.parent().parent('tr.attribute-options-row'),
                            filterId = del.attr('data-filter-id');

                        if (filterId) {
                            confirm({
                                title: $t('Are you sure want to delete this filter?'),
                                actions: {
                                    confirm: function () {
                                        $.ajax({
                                            method: 'POST',
                                            url: self.options.data.deleteFilterUrl,
                                            data: {
                                                formKey: window.FORM_KEY,
                                                filterId: filterId
                                            },
                                            dataType: 'json',
                                            showLoader: true
                                        }).done(function (response) {
                                            if (self.options.data.mode === 'auto') {
                                                rows.remove();
                                            } else {
                                                rows.remove();
                                                $('#mppf-add-product-modal').html(response.products);
                                                $('#mpproductfinder_products_grid').html(response.grid);
                                            }
                                        });
                                    }
                                }
                            });
                        } else {
                            rows.remove();
                        }
                    });
                }
            }
        );

        return $.mageplaza.filters;
    }
);
