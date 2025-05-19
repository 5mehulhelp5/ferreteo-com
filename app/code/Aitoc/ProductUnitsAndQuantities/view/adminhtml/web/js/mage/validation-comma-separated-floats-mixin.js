/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    'jquery'
], function ($) {
    "use strict";

    return function () {
        $.validator.addMethod(
            'validate-comma-separated-floats',
            function (value) {
                var chunks = value.split(',');

                var hasError = false;

                $.each(chunks, function (key, value) {
                    var trimmedValue = value.trim();
                    if (parseFloat(trimmedValue) != trimmedValue) {
                        hasError = true;

                        return false;
                    }
                });

                return !hasError;
            },
            $.mage.__('Not valid comma-separated floats string.')
        );
    }
});
