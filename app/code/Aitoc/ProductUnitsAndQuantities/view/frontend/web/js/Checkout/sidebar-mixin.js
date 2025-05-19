/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define(['jquery', 'jquery/ui'], function ($) {
    'use strict';

    return function () {
        //todo: will be fixed in 2.2.5. Add current version check.
        /**
         * @see https://github.com/magento/magento2/pull/14935
         */
        $.widget('mage.sidebar', $.mage.sidebar, {
            _initContent: function () {
                this._super();

                var self = this,
                    events = {};

                events['change ' + this.options.item.qty] = function (event) {
                    self._showItemButton($(event.target));
                };

                this._on(this.element, events);
            }
        });
    }
});
