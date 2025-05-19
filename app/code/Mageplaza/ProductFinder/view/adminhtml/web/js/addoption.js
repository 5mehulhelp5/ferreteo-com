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
        'jquery'
    ], function ($) {
        "use strict";

        var defaultTable    = $('.mppf-table-filter-options-manual'),
            tableContent    = defaultTable.find('#default-table-content'),
            defaultRowClass = 'default-row-';

        $.widget(
            'mageplaza.addoption',
            {
                _create: function () {
                    var self = this;

                    self.addDefaultOption(self);
                    self.deleteRow(self);
                },

                addDefaultOption: function (self) {
                    var countSelect = 0,
                        countRow    = tableContent.find('.attribute-options-row'),
                        addBtn      = defaultTable.find('.add_new_option_button');

                    //set count row number
                    if (countRow.length) {
                        countSelect = countRow.length;
                    }

                    if (addBtn.length) {
                        $('.add_new_option_button').on('click', function () {
                            if (tableContent.length) {
                                countSelect++;
                                self.createRow(self, countSelect);
                            }
                        });
                    }
                },

                createRow: function (self, count) {
                    var html = '<tr class="attribute-options-row ' + defaultRowClass + count + '">';

                    html += '<td><input type="text" name="option[' + count + '][name]" required></td>';
                    html += '<td><input type="file" name="image-' + count + '"></td>';
                    html += self.createStoreColumn(count, self);
                    html += '<td><a class="delete-attribute-row" data-option-number="' + count + '">';
                    html += '<i class="action-delete"></i></a></td></tr>';

                    $('#default-table-content').append(html);
                },

                createStoreColumn: function (count, self) {
                    var html = '', i;

                    for (i = 0; i < self.options.storeCount; i++){
                        html += '<td><input type="text" name="option[' + count + '][store][' + i + ']"';
                        if (i === 0) {
                            html += ' required></td>';
                        } else {
                            html += '></td>';
                        }
                    }

                    return html;
                },

                deleteRow: function (self) {
                    $(document).on('click', 'a.delete-attribute-row', function () {
                        var del      = $(this),
                            rows     = del.parent().parent('tr.attribute-options-row'),
                            optionId = del.attr('data-option-id');

                        if (optionId) {
                            $.ajax({
                                method: 'POST',
                                url: self.options.deleteOptionUrl,
                                data: {
                                    filterId: self.options.filterId,
                                    optionId: optionId
                                },
                                showLoader: true
                            }).done(function () {
                                rows.remove();
                            });
                        } else {
                            rows.remove();
                        }
                    });
                }
            }
        );

        return $.mageplaza.addoption;
    }
);
