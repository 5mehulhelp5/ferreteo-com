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
 * Class MaxQtyValidator
 */
class EndQtyValidator extends Base
{
    /**
     * @inheritdoc
     */
    protected function getErrorMessageFieldName()
    {
        return StartEndIncQtyLabelInterface::END_QTY;
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

        $endQty = $value->getEndQty();

        if (!$this->isValidNumberType($value, $endQty)) {
            return false;
        }

        // Is greater or equal to min
        $startQty = $value->getStartQty();

        $minValueName = '"' . StartEndIncQtyLabelInterface::START_QTY . '"';

        if (!$this->isGreaterOrEqualTo($endQty, $startQty, $minValueName)) {
            return false;
        }

        return true;
    }
}
