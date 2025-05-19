<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Helper\Validator\ProductPuqConfig\Base;

use Magento\Framework\Validator\AbstractValidator;
use Zend_Validate_InArray;

/**
 * Class BaseInArrayValidator
 */
abstract class BaseInArrayValidator extends AbstractValidator
{
    /**
     * @return string
     */
    abstract protected function getErrorMessage();

    /**
     * @return array
     */
    abstract protected function getPossibleValues();

    /**
     * @var Zend_Validate_InArray
     */
    private $zendValidateInArray;

    /**
     * BaseInArrayValidator constructor.
     * @throws \Zend_Validate_Exception
     */
    public function __construct()
    {
        $options = [
            'haystack' => $this->getPossibleValues()
        ];

        $this->zendValidateInArray = new Zend_Validate_InArray($options);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        $this->_clearMessages();

        $isValid = $this->zendValidateInArray->isValid($value);

        if (!$isValid) {
            $this->_addMessages([$this->getErrorMessage()]);
        }

        return $isValid;
    }
}
