/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define(["Aitoc_ProductUnitsAndQuantities/js/constants/qty-types"], function (QTY_TYPES) {
    return {

        getQuantitiesAsArrayOfFloat: function (config) {
            var quantities = (config.qty_type === QTY_TYPES.DYNAMIC)
                ? getDynamicQuantities(config)
                : getStaticQuantities(config);

            if (config.allow_zero) {
                quantities.unshift(0);
            }

            quantities.sort(function (a, b) {
                return a - b;
            });

            return fixQuantitiesPrecision(quantities);

            function fixQuantitiesPrecision(quantities)
            {
                var fixedQuantities = [];

                quantities.forEach(function (item, i) {
                    fixedQuantities[i] = parseFloat(parseFloat(item).toFixed(3));
                });

                return fixedQuantities;
            }
        }
    };

    function getDynamicQuantities(config)
    {
        var startQty = config.start_qty;
        // todo: if max qty not set? or 0?
        var endQty = config.end_qty;
        var qtyIncrement = config.qty_increment;

        var dynamicUseQuantities = [startQty];
        var currentQty = startQty + qtyIncrement;

        while (currentQty <= endQty) {
            dynamicUseQuantities.push(currentQty);
            currentQty += qtyIncrement;
        }

        return dynamicUseQuantities;
    }

    function getStaticQuantities(config)
    {
        return getUseQuantitiesAsArrayOfFloat(config.use_quantities);
    }

    function getUseQuantitiesAsArrayOfFloat(quantities)
    {
        quantities = quantities.replace(/\s+/g, '');
        quantities = quantities.split(',');

        quantities = jQuery.each(quantities, function (index, str) {
            quantities[index] = parseFloat(str);
        });

        return quantities;
    }
});
