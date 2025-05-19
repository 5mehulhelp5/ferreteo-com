/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define(
    ["Aitoc_ProductUnitsAndQuantities/js/quantities/constants/bundle-product-option-types"],
    function (BUNDLE_PRODUCT_OPTION_TYPES) {
        return {
            getSelectionIdByElement: function (element) {
                var selectionType = getSelectionType(element);

                return getSelectionIdIdByElementType(element, selectionType);

                function getSelectionType($element)
                {
                    return $element.prop('type');
                }

                function getSelectionIdIdByElementType(element, selectionType)
                {
                    switch (selectionType) {
                        case BUNDLE_PRODUCT_OPTION_TYPES.RADIO:
                        case BUNDLE_PRODUCT_OPTION_TYPES.SELECT_ONE:
                        case BUNDLE_PRODUCT_OPTION_TYPES.CHECKBOX:
                        case BUNDLE_PRODUCT_OPTION_TYPES.HIDDEN:
                            return getElementVal(element) || null;
                        case BUNDLE_PRODUCT_OPTION_TYPES.SELECT_MULTIPLE:
                            throw  new Error('Select multiple should be unreachable.');
                        default:
                            throw new Error('Unknown selection type:' + selectionType);
                    }

                    function getElementVal($element)
                    {
                        // return $element.val() || null;
                        return $element.val();
                    }
                }
            }
        }
    }
);
