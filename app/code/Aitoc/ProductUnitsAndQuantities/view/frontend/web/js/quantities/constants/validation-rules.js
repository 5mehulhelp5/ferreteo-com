/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([], function () {
    return {
        VALIDATE_ITEM_QUANTITY: 'validate-item-quantity',
        VALIDATE_GROUPED_ITEM_QUANTITY: 'validate-grouped-item-quantity',
        //Like VALIDATE_ITEM_QUANTITY but allow 0. Used in this ext for grouped product items.
        //At the moment (v2.2.4) item grouped product items not validated
        //todo:on-magento-release check on new release (after v2.2.4)

        VALIDATE_BY_QUANTITIES: 'validate-by-quantities'
    };
});
