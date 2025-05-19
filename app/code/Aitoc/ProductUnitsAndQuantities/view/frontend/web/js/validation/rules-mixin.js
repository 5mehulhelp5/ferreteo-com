/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define(
    [
        'lib/utils',
        'Aitoc_ProductUnitsAndQuantities/js/config',
        'Aitoc_ProductUnitsAndQuantities/js/config',
        'Aitoc_ProductUnitsAndQuantities/js/quantities/constants/validation-rules'
    ],
    /**
     * @param utils
     * @param puqConfig {{
     *  fix_min_max_sale_qty_error_messages:string,
     *  minAllowed:float,
     *  maxAllowed: float,
     *  qtyIncrements: float
     * }}
     * @returns {Function}
     */
    function (utils, puqConfig, VALIDATION_RULES) {
        'use strict';

        return function (target) {

            if (!puqConfig.fix_min_max_sale_qty_error_messages) {
                return target;
            }

            /** @see https://github.com/magento/magento2/pull/13942 */
            target[VALIDATION_RULES.VALIDATE_ITEM_QUANTITY] = {
                handler: function (value, params) {
                    var validator = this,
                        // obtain values for validation
                        qty = utils.parseNumber(value),
                        isMinAllowedValid = typeof params.minAllowed === 'undefined' ||
                            qty >= utils.parseNumber(params.minAllowed.toString()),
                        isMaxAllowedValid = typeof params.maxAllowed === 'undefined' ||
                            qty <= utils.parseNumber(params.maxAllowed.toString()),
                        isQtyIncrementsValid = typeof params.qtyIncrements === 'undefined' ||
                            qty % utils.parseNumber(params.qtyIncrements.toString()) === 0;

                    var result = qty > 0;

                    if (result === false) {
                        validator.itemQtyErrorMessage = $.mage.__('Please enter a quantity greater than 0.');

                        return result;
                    }

                    result = isMinAllowedValid;

                    if (result === false) {
                        validator.itemQtyErrorMessage = $.mage.__('The fewest you may purchase is %1.')
                            .replace('%1', params.minAllowed);

                        return result;
                    }

                    result = isMaxAllowedValid;

                    if (result === false) {
                        validator.itemQtyErrorMessage = $.mage.__('The maximum you may purchase is %1.')
                            .replace('%1', params.maxAllowed);

                        return result;
                    }

                    result = isQtyIncrementsValid;

                    if (result === false) {
                        validator.itemQtyErrorMessage = $.mage
                            .__('You can buy this product only in quantities of %1 at a time.')
                            .replace('%1', params.qtyIncrements);

                        return result;
                    }

                    return result;
                },
                message: function () {
                    return this.itemQtyErrorMessage;
                }
            };

            return target;
        };
    }
);
