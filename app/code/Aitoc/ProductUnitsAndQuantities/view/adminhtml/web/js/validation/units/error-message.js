/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    'jquery',
    'Aitoc_ProductUnitsAndQuantities/js/constants/labels/units-labels',
    'mage/translate'
], function ($, unitsLabels) {
    return $.mage.__('"%1" or "%2" should be not empty when "%3" enabled.')
        .replace('%1', $.mage.__(unitsLabels.PRICE_PER))
        .replace('%2', $.mage.__(unitsLabels.PRICE_PER_DIVIDER))
        .replace('%3', $.mage.__(unitsLabels.ALLOW_UNITS));
});
