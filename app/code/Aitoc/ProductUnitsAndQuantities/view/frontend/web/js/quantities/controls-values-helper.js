/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    "jQuery",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/constants/known-custom-controls",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/ui-widget-helper",
    "Aitoc_ProductUnitsAndQuantities/js/quantities/controls/all"
], function (KNOWN_CUSTOM_CONTROLS, uiWidgetHelper) {

    return {
        getValue: function (customControl) {
            var CustomControlType = getCustomControlType(customControl);

            return customControl[CustomControlType]('value');
        }
    };

    function getCustomControlType(customControl)
    {
        return uiWidgetHelper.getCustomControlNameOrNull(customControl);
    }
});
