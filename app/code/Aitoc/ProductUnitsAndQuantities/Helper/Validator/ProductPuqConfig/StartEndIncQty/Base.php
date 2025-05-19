<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Helper\Validator\ProductPuqConfig\StartEndIncQty;

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfig\PuqConfigWithoutUseConfigGettersInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\QtyTypeInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\ReplaceQtyInterface;
use Magento\Framework\Validator\AbstractValidator;
use Magento\Framework\Validator\FloatUtils;
use Magento\Framework\Validator\IntUtils;
use Zend_Validate_GreaterThan;

/**
 * Class Base
 */
abstract class Base extends AbstractValidator
{
    /**
     * @return mixed
     */
    abstract protected function getErrorMessageFieldName();

    const ERROR_MESSAGE_NOT_INT = '"%1" isn\'t integer.';
    const ERROR_MESSAGE_NOT_FLOAT = '"%1" isn\'n decimal.';
    const ERROR_MESSAGE_LESS_THEN_ZERO = '"%1" should be greater then %2.';
    const ERROR_MESSAGE_GREATER_THEN_MAX = '"%1" should be less or equal to %2.';

    const MIN_START_QTY = 0;
    const MIN_QTY_INCREMENT = 0;

    /**
     * @var IntUtils
     */
    protected $intValidator;

    /**
     * @var FloatUtils
     */
    protected $floatValidator;

    /**
     * Base constructor.
     * @param IntUtils $intValidator
     * @param FloatUtils $floatValidator
     */
    public function __construct(
        IntUtils $intValidator,
        FloatUtils $floatValidator
    ) {
        $this->intValidator = $intValidator;
        $this->floatValidator = $floatValidator;
    }

    /**
     * @param string $errorMessage
     * @param null $arg
     */
    protected function addErrorMessage($errorMessage, $arg = null)
    {
        $messages = [__($errorMessage, $this->getErrorMessageFieldName(), $arg)];
        $this->_addMessages($messages);
    }

    /**
     * @param PuqConfigWithoutUseConfigGettersInterface $value
     * @return bool
     */
    protected function validationRequired(PuqConfigWithoutUseConfigGettersInterface $value)
    {
        if ($value->getReplaceQty() == ReplaceQtyInterface::OFF) {
            return false;
        }

        if ($value->getQtyType() != QtyTypeInterface::TYPE_DYNAMIC) {
            return false;
        }

        return true;
    }

    /**
     * @param PuqConfigWithoutUseConfigGettersInterface $puqConfig
     * @param int|float $number
     * @return bool
     */
    protected function isValidNumberType(PuqConfigWithoutUseConfigGettersInterface $puqConfig, $number)
    {
        $isQtyDecimal = $puqConfig->getIsQtyDecimal();

        $numberValidator = $isQtyDecimal
            ? $this->floatValidator
            : $this->intValidator;

        // Is float or int.
        if (!$numberValidator->isValid($number)) {
            $message = $isQtyDecimal ? self::ERROR_MESSAGE_NOT_FLOAT : self::ERROR_MESSAGE_NOT_INT;
            $this->addErrorMessage($message);

            return false;
        }

        return true;
    }

    /**
     * @param int|float $value
     * @param int|float $min
     * @param string $messageCode
     * @param null $arg
     * @return bool
     * @throws \Zend_Validate_Exception
     */
    protected function isGreaterThenWithMessageCode($value, $min, $messageCode, $arg = null)
    {
        $greaterThanValidator = new Zend_Validate_GreaterThan($min);

        if (!$greaterThanValidator->isValid($value)) {
            $minMessageArg = $arg ? $arg : $min;
            $this->addErrorMessage($messageCode, $minMessageArg);

            return false;
        }

        return true;
    }

        /**
         * @param int|float $value
         * @param int|float $min
         * @return bool
         * @throws \Zend_Validate_Exception
         */
    protected function isGreaterThen($value, $min)
    {
        return $this->isGreaterThenWithMessageCode($value, $min, self::ERROR_MESSAGE_LESS_THEN_ZERO);
    }

    /**
     * @param int|float $value
     * @param int|float $max
     * @param null $limValueName
     * @return bool
     * @throws \Zend_Validate_Exception
     */
    protected function isLessOrEqualTo($value, $max, $limValueName = null)
    {
        return $this->isLessOrEqualToWithMessageCode($value, $max, self::ERROR_MESSAGE_GREATER_THEN_MAX, $limValueName);
    }

    /**
     * @param int|float $value
     * @param int|float $max
     * @param string $messageCode
     * @param mixed $arg
     * @return bool
     * @throws \Zend_Validate_Exception
     */
    protected function isLessOrEqualToWithMessageCode($value, $max, $messageCode, $arg = null)
    {
        $greaterThanValidator = new Zend_Validate_GreaterThan($max);

        if ($greaterThanValidator->isValid($value)) {
            $minMessageArg = $arg ? $arg : $max;
            $this->addErrorMessage($messageCode, $minMessageArg);

            return false;
        }

        return true;
    }

    /**
     * @param int|float $value
     * @param int|float $max
     * @param null $limValueName
     * @return bool
     * @throws \Zend_Validate_Exception
     */
    protected function isGreaterOrEqualTo($value, $max, $limValueName = null)
    {
        return $this->isGreaterOrEqualToWithMessageCode(
            $value,
            $max,
            self::ERROR_MESSAGE_GREATER_THEN_MAX,
            $limValueName
        );
    }

    /**
     * @param int|float $value
     * @param int|float $min
     * @param string $messageCode
     * @param mixed $arg
     * @return bool
     * @throws \Zend_Validate_Exception
     */
    protected function isGreaterOrEqualToWithMessageCode($value, $min, $messageCode, $arg = null)
    {
        $lessThanValidator = new \Zend_Validate_LessThan($min);

        if ($lessThanValidator->isValid($value)) {
            $maxMessageArg = $arg ? $arg : $min;
            $this->addErrorMessage($messageCode, $maxMessageArg);

            return false;
        }

        return true;
    }
}
