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

use Aitoc\ProductUnitsAndQuantities\Api\Data\ProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\QtyTypeInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\ReplaceQtyInterface;
use Magento\Framework\Validator\AbstractValidator;

/**
 * Class UseQuantitiesValidator
 */
class UseQuantitiesValidator extends AbstractValidator
{
    /**
     * @param ProductPuqConfigInterface $value
     * @return bool
     */
    public function isValid($value)
    {
        $this->_clearMessages();

        if ($value->getReplaceQty() == ReplaceQtyInterface::OFF) {
            return true;
        }

        if ($value->getQtyType() != QtyTypeInterface::TYPE_STATIC) {
            return true;
        }

        $useQuantities = $value->getUseQuantities();
        $isQtyDecimal = $value->getIsQtyDecimal();

        $values = explode(',', $useQuantities);

        foreach ($values as $value) {
            $isValid = true;

            if (!is_numeric($value)) {
                $isValid = false;
            } elseif (!$isQtyDecimal && !is_int($value + 0)) {
                $isValid = false;
            }

            if (!$isValid) {
                $expectedType = $isQtyDecimal ? 'fractional number' : 'whole number';
                $messages = [];
                $messages[] = __('Invalid "Use quantities" value: "%1". Enter %2.', $value, $expectedType);
                $this->_addMessages($messages);

                return false;
            }
        }

        return true;
    }
}
