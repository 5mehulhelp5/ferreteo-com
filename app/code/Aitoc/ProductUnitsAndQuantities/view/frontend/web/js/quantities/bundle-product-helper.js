/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([], function () {
    return {
        getElementOptionType: function ($element) {
            return $element.prop('type');
        }
    }
});
