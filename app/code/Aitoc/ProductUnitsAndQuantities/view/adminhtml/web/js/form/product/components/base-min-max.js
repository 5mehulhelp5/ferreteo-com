/*
 * Copyright © 2019 Aitoc. All rights reserved.
 */

define([
    'Aitoc_ProductUnitsAndQuantities/js/form/product/components/base-min-max-qty'
], function (BaseMinMaxQty) {
    return BaseMinMaxQty.extend({
        getValidation: function (datasourceProduct) {
            var validation = this._super();

            this.addLinkedFieldValidationIfRequired(datasourceProduct, validation);

            return validation;
        },

        addLinkedFieldValidationIfRequired: function (datasourceProduct, validation) {
            var relatedFieldKey = this.getRelatedFieldKey();
            var relatedFieldIndex = this.getUseConfigKey(relatedFieldKey);

            if (datasourceProduct[relatedFieldIndex]) {
                return;
            }

            var relatedFieldValidatorName = this.getRelatedFieldValidatorName();

            validation[relatedFieldValidatorName] = Number(datasourceProduct[relatedFieldKey]);
        }
    });
});
