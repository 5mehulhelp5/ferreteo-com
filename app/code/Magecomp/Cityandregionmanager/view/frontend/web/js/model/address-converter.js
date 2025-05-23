define([
    'jquery',
    'Magento_Checkout/js/model/new-customer-address',
    'Magento_Customer/js/customer-data',
    'mage/utils/objects'
], function ($, address, customerData, mageUtils) {
    'use strict';

    var countryData = customerData.get('directory-data');

    return {
        formAddressDataToQuoteAddress: function (formData) {
            var addressData = $.extend(true, {}, formData),
                region,
                regionName = addressData.region;

            if (mageUtils.isObject(addressData.street)) {
                addressData.street = this.objectToArray(addressData.street);
            }

            addressData.region = {
                'region_id': addressData['region_id'],
                'region_code': addressData['region_code'],
                region: regionName
            };

            if (addressData['region_id'] &&
                countryData()[addressData['country_id']] &&
                countryData()[addressData['country_id']].regions
            ) {
                region = countryData()[addressData['country_id']].regions[addressData['region_id']];

                if (region) {
                    addressData.region['region_id'] = addressData['region_id'];
                    addressData.region['region_code'] = region.code;
                    addressData.region.region = region.name;
                }
            } else if (
                !addressData['region_id'] &&
                countryData()[addressData['country_id']] &&
                countryData()[addressData['country_id']].regions
            ) {
                addressData.region['region_code'] = '';
                addressData.region.region = regionName;
            }
            delete addressData['region_id'];

            return address(addressData);
        },

        quoteAddressToFormAddressData: function (addrs) {
            var self = this,
                output = {},
                streetObject;

            if ($.isArray(addrs.street)) {
                streetObject = {};
                addrs.street.forEach(function (value, index) {
                    streetObject[index] = value;
                });
                addrs.street = streetObject;
            }

            $.each(addrs, function (key) {
                if (addrs.hasOwnProperty(key) && !$.isFunction(addrs[key])) {
                    output[self.toUnderscore(key)] = addrs[key];
                }
            });

            return output;
        },

        toUnderscore: function (string) {
            return string.replace(/([A-Z])/g, function ($1) {
                return '_' + $1.toLowerCase();
            });
        },

        formDataProviderToFlatData: function (formProviderData, formIndex) {
            var addressData = {};

            $.each(formProviderData, function (path, value) {
                var pathComponents = path.split('.'),
                    dataObject = {};

                pathComponents.splice(pathComponents.indexOf(formIndex), 1);
                pathComponents.reverse();
                $.each(pathComponents, function (index, pathPart) {
                    var parent = {};

                    if (index == 0) { //eslint-disable-line eqeqeq
                        dataObject[pathPart] = value;
                    } else {
                        parent[pathPart] = dataObject;
                        dataObject = parent;
                    }
                });
                $.extend(true, addressData, dataObject);
            });

            return addressData;
        },

        objectToArray: function (object) {
            var convertedArray = [];

            $.each(object, function (key) {
                return typeof object[key] === 'string' ? convertedArray.push(object[key]) : false;
            });

            return convertedArray.slice(0);
        },

        addressToEstimationAddress: function (addrs) {
            var self = this,
                estimatedAddressData = {};

            $.each(addrs, function (key) {
                estimatedAddressData[self.toUnderscore(key)] = addrs[key];
            });

            return this.formAddressDataToQuoteAddress(estimatedAddressData);
        }
    };
});
