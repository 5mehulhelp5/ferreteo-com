/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([], function () {
    // should be synced with \Aitoc\ProductUnitsAndQuantities\Helper\UseQuantitiesHelper
    return {
        getNearestValue: function (value, values) {
            var diff = absDiff(values[0], value);
            var nearest = values[0];

            jQuery.each(values, function (index, itemValue) {
                var newDiff = absDiff(itemValue, value);
                if (newDiff > diff) {
                    return false;
                } else if (newDiff < diff) {
                    diff = newDiff;
                    nearest = itemValue;
                }
            });

            return nearest;

            function absDiff(a, b)
            {
                return Math.abs(a - b);
            }
        },

        getDownValue: function (value, localUseQuantities) {
            var minValue = localUseQuantities[0];

            if (value <= minValue) {
                return minValue;
            }

            var nearestValue = this.getNearestValue(value, localUseQuantities);

            if (nearestValue < value) {
                return nearestValue;
            }

            var nearestValueIndex = localUseQuantities.indexOf(nearestValue);

            if (nearestValueIndex === 0) {
                return nearestValue;
            }

            return localUseQuantities[nearestValueIndex - 1];
        },

        getUpValue: function (value, localUseQuantities) {
            var maxValueIndex = localUseQuantities.length - 1;
            var maxValue = localUseQuantities[maxValueIndex];

            if (value >= maxValue) {
                return maxValue;
            }

            var nearestValue = this.getNearestValue(value, localUseQuantities);

            if (nearestValue > value) {
                return nearestValue;
            }

            var nearestValueIndex = localUseQuantities.indexOf(nearestValue);

            if (nearestValueIndex === maxValueIndex) {
                return nearestValue;
            }

            return localUseQuantities[nearestValueIndex + 1];
        }
    }
});
