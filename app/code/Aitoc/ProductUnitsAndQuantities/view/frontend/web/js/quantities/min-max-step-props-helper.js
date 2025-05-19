/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([], function () {
    return {
        getMinMaxStepPropsAsObject: function (element) {
            return {
                min: element.prop('min'),
                max: element.prop('max'),
                step: element.prop('step')
            }
        },

        setMinMaxStepPropsByObject: function (element, minMaxStepObject) {
            this.setMinMaxStepProps(element, minMaxStepObject['min'], minMaxStepObject['max'], minMaxStepObject['step']);
        },

        setMinMaxStepProps: function (element, min, max, step) {
            element.prop('min', min);
            element.prop('max', max);
            element.prop('step', step);
        }
    }
});
