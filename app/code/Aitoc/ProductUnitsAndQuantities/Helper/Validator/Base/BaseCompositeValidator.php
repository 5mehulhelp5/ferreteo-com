<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Helper\Validator\Base;

use Magento\Framework\Validator\AbstractValidator;
use Magento\Framework\Validator\ValidatorInterface;

/**
 * Class BaseCompositeValidator
 */
abstract class BaseCompositeValidator extends AbstractValidator
{
    /**
     * @param ValidatorInterface $validator
     * @param mixed $value
     * @return bool
     * @throws \Zend_Validate_Exception
     */
    protected function validateByValidator(ValidatorInterface $validator, $value)
    {
        $isValid = $validator->isValid($value);

        if (!$isValid) {
            $this->_addMessages($validator->getMessages());
        }

        return $isValid;
    }
}
