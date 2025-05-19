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
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfig\PuqConfigWithoutUseConfigGettersInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\QtyTypeInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\ReplaceQtyInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\PuqConfig\Adjuster\ByAspect\Qty\ByIncrementsQtyAdjuster;
use Magento\Framework\Validator\AbstractValidator;

/**
 * Class StepsCountValidator
 */
class StepsCountValidator extends AbstractValidator
{
    const MAX_STEPS_COUNT = 100;//Aitoc/ProductUnitsAndQuantities/view/adminhtml/web/js/validation/qty-max-steps-count-mixin.js:MAX_STEPS_COUNT

    const ERROR_MESSAGE = 'Too many possible values for product quantities. Maximum is %1.<br/>Either increase values in field "Minimum Qty Allowed in Shopping Cart" or "Qty Increments". Alternatively, decrease value in field "Maximum Qty Allowed in Shopping Cart".';

    private $byIncrementsQtyAdjuster;

    /**
     * StepsCountValidator constructor.
     * @param ByIncrementsQtyAdjuster $byIncrementsQtyAdjuster
     */
    public function __construct(ByIncrementsQtyAdjuster $byIncrementsQtyAdjuster)
    {
        $this->byIncrementsQtyAdjuster = $byIncrementsQtyAdjuster;
    }

    /**
     * @param RealProductPuqConfigInterface $value
     * @return bool
     */
    public function isValid($value)
    {
        $this->_clearMessages();

        if (!$this->validationRequired($value)) {
            return true;
        }

        $stepsCount = $this->getStepsCount($value);

        if ($stepsCount > self::MAX_STEPS_COUNT) {
            $message = __(self::ERROR_MESSAGE, self::MAX_STEPS_COUNT, $stepsCount);
            $this->_addMessages([$message]);

            return false;
        }

        return true;
    }

    /**
     * @param PuqConfigWithoutUseConfigGettersInterface $value
     * @return bool
     */
    private function validationRequired(PuqConfigWithoutUseConfigGettersInterface $value)
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
     * @param PuqConfigWithoutUseConfigGettersInterface $value
     * @return float|int
     */
    private function getStepsCount(PuqConfigWithoutUseConfigGettersInterface $value)
    {
        $minQty = $value->getStartQty();
        $maxQty = $value->getEndQty();
        $incQty = $value->getQtyIncrement();

        $qtyAdjuster = $this->byIncrementsQtyAdjuster;
        $adjustedMinQty = $qtyAdjuster->getAdjustedMinValue($minQty, $incQty);
        $adjustedMaxQty = $qtyAdjuster->getAdjustedMinValue($maxQty, $incQty);

        return $this->getStepsCountByResultQtyParams($adjustedMinQty, $adjustedMaxQty, $incQty);
    }

    /**
     * @param float $minQty
     * @param float $maxQty
     * @param float $incQty
     * @return float|int
     */
    private function getStepsCountByResultQtyParams($minQty, $maxQty, $incQty)
    {
        return 1 + (($maxQty - $minQty) / $incQty);
    }
}
