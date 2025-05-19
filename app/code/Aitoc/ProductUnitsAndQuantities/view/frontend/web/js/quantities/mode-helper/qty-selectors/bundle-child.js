/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define(['jquery', 'jquery/ui'], function (jQuery) {
    return function (priceElement) {
        return jQuery(priceElement).closest('.control').find('input[type=number]');
    }
});
