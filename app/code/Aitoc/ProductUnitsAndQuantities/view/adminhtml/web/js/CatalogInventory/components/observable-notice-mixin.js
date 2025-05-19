/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define(['ko'], function (ko) {
    return function (target) {
        return target.extend({
            initObservable: function () {
                var orig = this._super();

                //`notice` is observable starts from v2.2
                if (ko.isObservable(orig.notice)) {
                    return orig;
                }

                return orig.observe(['notice']);
            }
        });
    };
});
