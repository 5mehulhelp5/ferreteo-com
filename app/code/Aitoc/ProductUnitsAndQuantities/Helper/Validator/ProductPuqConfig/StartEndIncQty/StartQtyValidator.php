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

use Aitoc\ProductUnitsAndQuantities\Api\Data\RealProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\Labels\StartEndIncQtyLabelInterface;

/**
 * Class StartQtyValidator
 */
class StartQtyValidator extends Base
{
    /**
     * @inheritdoc
     */
    protected function getErrorMessageFieldName()
    {
        return StartEndIncQtyLabelInterface::START_QTY;
    }

    /**
     * @param RealProductPuqConfigInterface $value
     * @return bool
     * @throws \Zend_Validate_Exception
     */
    public function isValid($value)
    {
        $this->_clearMessages();

        if (!$this->validationRequired($value)) {
            return true;
        }

        $startQty = $value->getStartQty();

        if (!$this->isValidNumberType($value, $startQty)) {
            return false;
        }

        // Is greater then min
        if (!$this->isGreaterThen($startQty, self::MIN_START_QTY)) {
            return false;
        }

        // Is less or equal to max
        $endQty = $value->getEndQty();
        $maxValueName = '"' . StartEndIncQtyLabelInterface::END_QTY . '"';

        if (!$this->isLessOrEqualTo($startQty, $endQty, $maxValueName)) {
            return false;
        }

        return true;
    }
}
