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
use Aitoc\ProductUnitsAndQuantities\Helper\Validator\Base\BaseCompositeValidator;
use Aitoc\ProductUnitsAndQuantities\Helper\Validator\ProductPuqConfig\StartEndIncQty\EndQtyValidator;
use Aitoc\ProductUnitsAndQuantities\Helper\Validator\ProductPuqConfig\StartEndIncQty\QtyIncrementValidator;
use Aitoc\ProductUnitsAndQuantities\Helper\Validator\ProductPuqConfig\StartEndIncQty\StartQtyValidator;
use Aitoc\ProductUnitsAndQuantities\Helper\Validator\ProductPuqConfig\StartEndIncQty\StepsCountValidator;

/**
 * Class MinMaxIncQtyValidator
 */
class StartEndIncQtyValidator extends BaseCompositeValidator
{
    /**
     * @var StartQtyValidator
     */
    private $startQtyValidator;

    /**
     * @var QtyIncrementValidator
     */
    private $qtyIncrementValidator;

    /**
     * @var EndQtyValidator
     */
    private $endQtyValidator;

    /**
     * @var StepsCountValidator
     */
    private $stepsCountValidator;

    /**
     * MinMaxIncQtyValidator constructor.
     * @param StartQtyValidator $StartQtyValidator
     * @param QtyIncrementValidator $qtyIncrementValidator
     * @param EndQtyValidator $endQtyValidator
     * @param StepsCountValidator $stepsCountValidator
     */
    public function __construct(
        StartQtyValidator $StartQtyValidator,
        QtyIncrementValidator $qtyIncrementValidator,
        EndQtyValidator $endQtyValidator,
        StepsCountValidator $stepsCountValidator
    ) {
        $this->startQtyValidator = $StartQtyValidator;
        $this->qtyIncrementValidator = $qtyIncrementValidator;
        $this->endQtyValidator = $endQtyValidator;
        $this->stepsCountValidator = $stepsCountValidator;
    }

    /**
     * @param RealProductPuqConfigInterface $value
     * @return bool
     * @throws \Zend_Validate_Exception
     */
    public function isValid($value)
    {
        $this->_clearMessages();

        if (!$ret = $this->validateStartQty($value)) {
            return $ret;
        }

        if (!$ret = $this->validateQtyIncrement($value)) {
            return $ret;
        }

        if (!$ret = $this->validateEndQty($value)) {
            return $ret;
        }

        if (!$ret = $this->validateStepsCount($value)) {
            return $ret;
        }

        return true;
    }

    /**
     * @param RealProductPuqConfigInterface $puqConfig
     * @return bool
     * @throws \Zend_Validate_Exception
     */
    public function validateStartQty(RealProductPuqConfigInterface $puqConfig)
    {
        return $this->validateByValidator($this->startQtyValidator, $puqConfig);
    }

    /**
     * @param RealProductPuqConfigInterface $puqConfig
     * @return bool
     * @throws \Zend_Validate_Exception
     */
    public function validateQtyIncrement(RealProductPuqConfigInterface $puqConfig)
    {
        return $this->validateByValidator($this->qtyIncrementValidator, $puqConfig);
    }

    /**
     * @param RealProductPuqConfigInterface $puqConfig
     * @return bool
     * @throws \Zend_Validate_Exception
     */
    public function validateEndQty(RealProductPuqConfigInterface $puqConfig)
    {
        return $this->validateByValidator($this->endQtyValidator, $puqConfig);
    }

    /**
     * @param RealProductPuqConfigInterface $puqConfig
     * @return bool
     * @throws \Zend_Validate_Exception
     */
    private function validateStepsCount(RealProductPuqConfigInterface $puqConfig)
    {
        return $this->validateByValidator($this->stepsCountValidator, $puqConfig);
    }
}
