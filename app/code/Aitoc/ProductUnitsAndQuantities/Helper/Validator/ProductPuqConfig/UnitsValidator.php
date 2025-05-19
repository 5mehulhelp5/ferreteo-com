<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Helper\Validator\ProductPuqConfig;

use Aitoc\ProductUnitsAndQuantities\Api\Data\RealProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\Labels\UnitsLabelInterface;
use Magento\Framework\Validator\AbstractValidator;

/**
 * Class UnitsValidator
 */
class UnitsValidator extends AbstractValidator
{
    // should be synced with Aitoc/ProductUnitsAndQuantities/view/adminhtml/web/js/validation/allow-units-rules-mixin.js
    const ERROR_MESSAGE = '"%1" or "%2" should be not empty when "%3" enabled.';

    /**
     * @param RealProductPuqConfigInterface $value
     * @return bool
     */
    public function isValid($value)
    {
        $this->_clearMessages();

        if (!$value->getAllowUnits()) {
            return true;
        }

        $pricePer = $value->getPricePer();
        $pricePerDivider = $value->getPricePerDivider();

        if (!empty($pricePer) || !empty($pricePerDivider)) {
            return true;
        }

        $message = __(
            self::ERROR_MESSAGE,
            UnitsLabelInterface::PRICE_PER,
            UnitsLabelInterface::PRICE_PER_DIVIDER,
            UnitsLabelInterface::ALLOW_UNITS
        );

        $this->_addMessages([$message]);

        return false;
    }
}
