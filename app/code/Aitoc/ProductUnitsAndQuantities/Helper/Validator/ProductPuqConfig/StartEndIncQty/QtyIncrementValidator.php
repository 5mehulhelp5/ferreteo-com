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

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\Labels\StartEndIncQtyLabelInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfig\PuqConfigWithoutUseConfigGettersInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\PuqConfig\Adjuster\ByAspect\Qty\ByIncrementsQtyAdjuster;
use Magento\Framework\Validator\FloatUtils;
use Magento\Framework\Validator\IntUtils;

/**
 * Class IncQtyValidator
 */
class QtyIncrementValidator extends Base
{
    const ERROR_MESSAGE
        = 'Adjusted by "Qty Increments" "Minimum/Maximum Qty Allowed in Shopping Cart" out of initial restricted values.';

    /**
     * @var ByIncrementsQtyAdjuster
     */
    private $byIncrementsQtyAdjuster;

    /**
     * QtyIncrementValidator constructor.
     * @param IntUtils $intValidator
     * @param FloatUtils $floatValidator
     * @param ByIncrementsQtyAdjuster $byIncrementsQtyAdjuster
     */
    public function __construct(
        IntUtils $intValidator,
        FloatUtils $floatValidator,
        ByIncrementsQtyAdjuster $byIncrementsQtyAdjuster
    ) {
        parent::__construct($intValidator, $floatValidator);
        $this->byIncrementsQtyAdjuster = $byIncrementsQtyAdjuster;
    }

    /**
     * @inheritDoc
     */
    public function isValid($value)
    {
        $this->_clearMessages();

        if (!$this->validationRequired($value)) {
            return true;
        }

        $incQty = $value->getQtyIncrement();

        if (!$this->isValidNumberType($value, $incQty)) {
            return false;
        }

        // Is greater then min
        if (!$this->isGreaterThen($incQty, self::MIN_QTY_INCREMENT)) {
            return false;
        }

        if (!$this->isNotBreakMinMaxAdjustedValues($incQty, $value)) {
            return false;
        }

        return true;
    }

    /**
     * @param int|float $incQty
     * @param PuqConfigWithoutUseConfigGettersInterface $value
     * @return bool
     */
    private function isNotBreakMinMaxAdjustedValues($incQty, PuqConfigWithoutUseConfigGettersInterface $value)
    {
        $minValue = $value->getStartQty();
        $maxValue = $value->getEndQty();

        $byIncrementsQtyAdjuster = $this->byIncrementsQtyAdjuster;

        $adjustedMinValue = $byIncrementsQtyAdjuster->getAdjustedMinValue($minValue, $incQty);
        $adjustedMaxValue = $byIncrementsQtyAdjuster->getAdjustedMaxValue($maxValue, $incQty);

        if ($adjustedMinValue > $adjustedMaxValue) {
            $message = __(self::ERROR_MESSAGE);
            $this->addErrorMessage($message);

            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    protected function getErrorMessageFieldName()
    {
        return StartEndIncQtyLabelInterface::QTY_INCREMENT;
    }
}
