/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/select'
], function (_, uiRegistry, select) {
    'use strict';
    //todo: implement by knockout bindings
    return select.extend({
        initialize: function () {
            this._super();
            this.changeDependableFieldsStatus(this.value());
            return this;
        },
        onUpdate: function (value) {
            this.changeDependableFieldsStatus(value);
            return this._super();
        },
        changeDependableFieldsStatus: function (value) {
            var staticMethod, dynamicMethod;
            switch (value) {
                //todo: use constants
                case 0:
                    staticMethod = 'enable';
                    dynamicMethod = 'disable';
                    break;
                case 1:
                    staticMethod = 'disable';
                    dynamicMethod = 'enable';
                    break;
            }

            // Change Static Qty Fields Status
            this.changeFieldState('use_quantities', staticMethod);
            // Change Dynamic Qty Fields Status
            this.changeFieldState('start_qty', dynamicMethod);
            this.changeFieldState('qty_increment', dynamicMethod);
            this.changeFieldState('end_qty', dynamicMethod);
        },
        changeFieldState: function (fieldIndex, method) {
            var fieldName = 'index = ' + fieldIndex;
            var useConfigCheckboxFieldName = 'index = use_config_' + fieldIndex;

            uiRegistry.get(
                [fieldName, useConfigCheckboxFieldName],
                function (field, useConfigCheckboxField) {
                    var fieldMethod = method;

                    if (useConfigCheckboxField.checked()) {
                        fieldMethod = 'disable';
                    }

                    field[fieldMethod]();
                    useConfigCheckboxField[method]();
                }
            );
        }
    });
});
