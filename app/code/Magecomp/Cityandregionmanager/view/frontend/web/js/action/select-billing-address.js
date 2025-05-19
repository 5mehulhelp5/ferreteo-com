define([
    'jquery',
    'Magento_Checkout/js/model/quote',
    'mage/url'
], function ($, quote,url) {
    'use strict';

    return function (billingAddress) {
        var address = null;
        setTimeout(function(){
            var bdselected_country = $('.billing-address-form select[name=country_id]').val();
            updateState(bdselected_country);
        }, 3000);
        var updateState = function (bselected_country) {
            var bstates_select = $('.billing-address-form div.state-drop-down div.control>select.select');
            var bcities_select = $('.billing-address-form div.city-drop-down>div.control>select.select');
            var bzip_select = $('.billing-address-form div.postcode-drop-down>div.control>select.select');
            bcities_select.empty().append($('<option data-title="Please Select..." value="">'+$.mage.__('Please Select')+'...</option>'));
            bzip_select.empty().append($('<option data-title="Please Select..." value="">'+$.mage.__('Please Select')+'...</option>'));
            if (bselected_country != "") {
                bstates_select.empty().append($('<option data-title="load" value="">'+$.mage.__('Load')+'...</option>'));
            }
            $.ajax({
                url: url.build('magecomp_cityandregionmanager/ajax/getstates'),
                type: 'post',
                data: {'selected_country': bselected_country},
                dataType: 'json',
                success: function (data) {
                    if (data.request == 'OK') {
                        bstates_select.empty().append($('<option data-title="Please Select..." value="">'+$.mage.__('Please Select')+'...</option>'));
                        $.each(data.result, function () {
                            bstates_select.append($('<option data-title="' + this.states_name + '" value="' + this.customstate + '">' + this.customstate + '</option>'));
                        });
                    } else {
                        bstates_select.empty().append($('<option data-title="' + data.result + '" value="' + data.result + '">' + data.result + '</option>'));
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
        $('.billing-address-form select[name=country_id]').change(function ()
        {
            updateState($(this).val());
        });
        $('.billing-address-form div.state-drop-down div.control>select.select').change(function ()
        {
            var bselected_state = $(this).find('option:selected').attr('data-title');
            var bcities_select = $('.billing-address-form div.city-drop-down>div.control>select.select');
            if ($(this).val() != "") {
                bcities_select.empty().append($('<option data-title="load" value="">'+$.mage.__('Load')+'...</option>'));
            }
            $.ajax({
                url: url.build('magecomp_cityandregionmanager/ajax/getcities'),
                type: 'post',
                data: {'selected_state': bselected_state},
                dataType: 'json',
                success: function (data) {
                    if (data.request == 'OK') {
                        bcities_select.empty().append($('<option data-title="Please Select..." value="">'+$.mage.__('Please Select')+'...</option>'));
                        $.each(data.result, function () {
                            bcities_select.append($('<option data-title="' + this.cities_name + '" value="' + this.customcity + '">' + this.customcity + '</option>'));
                        });
                    } else {
                        bcities_select.empty().append($('<option data-title="' + data.result + '" value="' + data.result + '">' + data.result + '</option>'));
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });
        $('.billing-address-form div.city-drop-down>div.control>select.select').change(function ()
        {
            var bselected_city = $(this).find('option:selected').attr('data-title');
            var bzip_select = $('.billing-address-form div.postcode-drop-down>div.control>select.select');
            bzip_select.empty().append($('<option data-title="load" value="">'+$.mage.__('Load')+'...</option>'));
            $.ajax({
                url: url.build('magecomp_cityandregionmanager/ajax/getzip'),
                type: 'post',
                data: {'selected_city': bselected_city},
                dataType: 'json',
                success: function (data) {
                    if (data.request == 'OK') {
                        if (window.valuesConfig == 1) {
                            jQuery('.postcode-text-box').show();
                        }
                        bzip_select.empty().append($('<option data-title="Please Select..." value="">'+$.mage.__('Please Select')+'...</option>'));
                        $.each(data.result, function () {
                            bzip_select.append($('<option data-title="' + this.zip_code + '" value="' + this.zip_code + '">' + this.zip_code + '</option>'));
                        });

                    } else {
                        bzip_select.empty().append($('<option data-title="' + data.result + '" value="' + data.result + '">' + data.result + '</option>'));
                    }


                },
                error: function (error) {
                    console.log(error);
                }
            });
        });
        if (quote.shippingAddress() && billingAddress.getCacheKey() == //eslint-disable-line eqeqeq
            quote.shippingAddress().getCacheKey()
        ) {
            address = $.extend({}, billingAddress);
            address.saveInAddressBook = null;
        } else {
            address = billingAddress;
        }
        quote.billingAddress(address);
    };
});
