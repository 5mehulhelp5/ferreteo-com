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
 * @category    Mageplaza
 * @package     Mageplaza_ProductFinder
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

define(
    [
        'jquery',
        'mage/translate',
        'mpChosen'
    ], function ($, $t) {
        "use strict";

        function showHideBtn (self, ruleSelect, count, filterCount) {
            if (self.resetOption === 'at_least_one') {
                if (count === 0) {
                    $(ruleSelect + ' .mppf-reset-btn').removeClass('active');
                }

                if (count !== 0) {
                    $(ruleSelect + ' .mppf-reset-btn').addClass('active');
                }
            }

            if (self.findOption !== 'always') {
                if (self.findOption === 'at_least_one') {
                    if (count === 0) {
                        $(ruleSelect + ' .mppf-find-btn').removeClass('active');
                    }

                    if (count !== 0) {
                        $(ruleSelect + ' .mppf-find-btn').addClass('active');
                    }
                }

                if (self.findOption === 'all') {
                    if (count < filterCount) {
                        $(ruleSelect + ' .mppf-find-btn').removeClass('active');
                    }

                    if (count === filterCount) {
                        $(ruleSelect + ' .mppf-find-btn').addClass('active');
                    }
                }
            }
        }

        function autoRedirect (self, ruleSelect, count, filterCount) {
            if (self.autoRedirect === '1') {
                if (count === filterCount) {
                    $(ruleSelect + ' .mppf-find-btn').trigger('click');
                }
            }
        }

        function resetUnselect (self, ruleId, ruleSelect) {
            if (self.resetUnselect === '1') {
                $('.mpproductfinder-select').each(function () {
                    var select = $('.mppf-select-filter-options', this);

                    if (select.attr('data-rule-id') !== ruleId) {
                        select.val('');
                        if (self.isChosen === '1') {
                            $('.chosen-single', this)
                            .html('<span>' + $t('Please select ...') + '</span><div><b></b></div>');
                        }
                    }
                });

                $('.mpproductfinder-finder-block').not($(ruleSelect)).each(function () {
                    $(this).find('.select-wrapper').attr('data-option-id', '')
                    .html('<span>' + $t('Please select ...') + '</span>');

                    if (self.findOption !== 'always') {
                        $('.mppf-find-btn', this).removeClass('active');
                    }

                    if (self.resetOption === 'at_least_one') {
                        $('.mppf-reset-btn', this).removeClass('active');
                    }
                });
            }
        }

        function countSelected (i, count, filterCount, ruleSelect) {
            var ddFilter, selectFilter;

            for (i; i < filterCount; i++){
                ddFilter     = $(ruleSelect + ' li:nth-child(' + (i + 1) + ') .select-wrapper');
                selectFilter = $(ruleSelect + ' li:nth-child(' + (i + 1) + ') .mppf-select-filter-options');

                if (ddFilter.length > 0 && ddFilter.attr('data-option-id') !== '') {
                    count++;
                }

                if (selectFilter.length > 0 && selectFilter.val() !== '') {
                    count++;
                }
            }

            return count;
        }

        $.widget(
            'mageplaza.mpproductfinder',
            {
                _create: function () {
                    var self = this.options;

                    this.setOptionAfterFind(self);
                    this.selectFilter(self);
                    this.dropdownFilter(self);
                    this.toggleDropDown(self);
                    this.checkResetCurrentPage(self);
                    if (self.isChosen === '1') {
                        $('.mppf-select-filter-options').chosen();
                    }
                },

                toggleDropDown: function (self) {
                    $('#mppf-block-' + self.ruleId + ' .mpproductfinder-dropdown').each(function () {
                        $(this).click(function (e) {
                            var dd = $(this).find('.dropdown-select-content');

                            dd.toggleClass('active');
                            $('.dropdown-select-content').not(dd).removeClass('active');
                            e.stopPropagation();
                        });

                        $('.mpproductfinder-close-btn', this).click(function () {
                            $('.dropdown-select-content', this).removeClass('active');
                        });
                    });

                    $('body').click(function () {
                        $('.dropdown-select-content').removeClass('active');
                    });
                },

                dropdownFilter: function (self) {
                    $('.dropdown-item').each(function () {
                        $(this).click(function () {
                            var input       = $(this).find('input'),
                                select      = $(this).parents('.mpproductfinder-dropdown').find('.select-wrapper'),
                                ruleId      = $(this).attr('data-rule-id'),
                                ruleSelect  = '.mpproductfinder-list-filter-' + ruleId,
                                filterCount = $(ruleSelect + ' li.mppf-filter-option').length,
                                count       = 0,
                                i           = 0,
                                selectedOption;

                            $(select).html('<span>' + input.attr('data-option-label') + '</span>');
                            input.attr('checked', 'checked');
                            $(select).attr('data-option-id', $(this).attr('data-option-id'));
                            selectedOption = countSelected(i, count, filterCount, ruleSelect);

                            autoRedirect(self, ruleSelect, selectedOption, filterCount);
                            showHideBtn(self, ruleSelect, selectedOption, filterCount);
                            resetUnselect(self, ruleId, ruleSelect);
                        });
                    });
                },

                selectFilter: function (self) {
                    $('.mppf-select-filter-options').each(function () {
                        $(this).change(function () {
                            var ruleId      = $(this).attr('data-rule-id'),
                                ruleSelect  = '.mpproductfinder-list-filter-' + ruleId,
                                filterCount = $(ruleSelect + ' li.mppf-filter-option').length,
                                count       = 0,
                                i           = 0,
                                selectedOption;

                            selectedOption = countSelected(i, count, filterCount, ruleSelect);

                            autoRedirect(self, ruleSelect, selectedOption, filterCount);
                            showHideBtn(self, ruleSelect, selectedOption, filterCount);
                            resetUnselect(self, ruleId, ruleSelect);
                        });
                    });
                },

                checkResetCurrentPage: function (self) {
                    $('.mpproductfinder-block').each(function () {
                        var resetBtn, findBtn, ddSelect, nativeSelect;

                        if (self.resetTo === 'current_page') {
                            resetBtn     = $('button.mppf-reset-btn', this);
                            findBtn      = $('button.mppf-find-btn', this);
                            ddSelect     = $('.select-wrapper', this);
                            nativeSelect = $('.mpproductfinder-select', this);

                            resetBtn.click(function () {
                                ddSelect.each(function () {
                                    $(this).html('<span>' + $t('Please select ...') + '</span>');
                                    $(this).attr('data-option-id', '');
                                });

                                if (self.isChosen === '1') {
                                    $(nativeSelect).find('.chosen-single')
                                    .html('<span>' + $t('Please select ...') + '</span><div><b></b></div>');
                                }

                                if (self.findOption !== 'always') {
                                    findBtn.removeClass('active');
                                }

                                if (self.resetOption === 'at_least_one') {
                                    resetBtn.removeClass('active');
                                }
                            });
                        }
                    });
                },

                setOptionAfterFind: function (self) {
                    var ruleId, selectBox, select, dropdown;

                    if (self.params) {
                        ruleId    = self.params.rule;
                        selectBox = $('ul.mpproductfinder-list-filter-' + ruleId);
                        select    = selectBox.find('.mpproductfinder-select');
                        dropdown  = selectBox.find('.mpproductfinder-dropdown');

                        if (self.params.filter.select.length !== 0 || self.params.filter.dropdown.length !== 0) {
                            selectBox.find('.mppf-find-btn').addClass('active');
                            if (self.resetOption !== 'no') {
                                selectBox.find('.mppf-reset-btn').addClass('active');
                            }
                        }
                        if (select.length > 0) {
                            select.each(function () {
                                var selectOption = $('.mppf-select-filter-options', this),
                                    labelSelect;

                                selectOption.val(self.params.filter.select[$(selectOption).attr('name')]);

                                if (self.isChosen === '1') {
                                    labelSelect = $('.mppf-select-filter-options option[value="'
                                        + self.params.filter.select[$(selectOption).attr('name')] + '"]', this).text();

                                    $('.chosen-single', this)
                                    .html('<span>' + labelSelect + '</span><div><b></b></div>');
                                }
                            });
                        }

                        if (dropdown.length > 0) {
                            dropdown.each(function () {
                                var filter        = $(this).attr('name'),
                                    selectWrapper = $('.select-wrapper', this),
                                    filterValue   = self.params.filter.dropdown[filter],
                                    label         = $('.dropdown-item input[value="' + filterValue + '"]')
                                    .attr('data-option-label');

                                if (filterValue) {
                                    selectWrapper.attr('data-option-id', self.params.filter.dropdown[filter]);
                                    $('.dropdown-item input[value="' + self.params.filter.dropdown[filter] + '"]')
                                    .prop('checked', true);
                                    selectWrapper.html('<span>' + label + '</span>');
                                }
                            });
                        }
                    }
                }
            }
        );

        return $.mageplaza.mpproductfinder;
    }
);
