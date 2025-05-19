/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([], function () {
    return function getMethodAsCallback(method, self)
    {
        return function () {
            return method.apply(self, arguments);
        }
    }
});
