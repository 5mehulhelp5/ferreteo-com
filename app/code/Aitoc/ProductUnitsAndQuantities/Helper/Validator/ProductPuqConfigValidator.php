<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Helper\Validator;

use Aitoc\ProductUnitsAndQuantities\Api\Data\RealProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\QtyTypeInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\Validator\Base\BaseCompositeValidator;
use Aitoc\ProductUnitsAndQuantities\Helper\Validator\ProductPuqConfig\IsQtyDecimalValidator;
use Aitoc\ProductUnitsAndQuantities\Helper\Validator\ProductPuqConfig\QtyTypeValidator;
use Aitoc\ProductUnitsAndQuantities\Helper\Validator\ProductPuqConfig\ReplaceQtyValidator;
use Aitoc\ProductUnitsAndQuantities\Helper\Validator\ProductPuqConfig\StartEndIncQtyValidator;
use Aitoc\ProductUnitsAndQuantities\Helper\Validator\ProductPuqConfig\UnitsValidator;
use Aitoc\ProductUnitsAndQuantities\Helper\Validator\ProductPuqConfig\UseQuantitiesValidator;

/**
 * Class ProductPuqConfigValidator
 */
class ProductPuqConfigValidator extends BaseCompositeValidator
{
    /**
     * @var UseQuantitiesValidator
     */
    private $useQuantitiesValidator;

    /**
     * @var ReplaceQtyValidator
     */
    private $replaceQtyValidator;

    /**
     * @var QtyTypeValidator
     */
    private $qtyTypeValidator;

    /**
     * @var IsQtyDecimalValidator
     */
    private $isQtyDecimalValidator;

    /**
     * @var StartEndIncQtyValidator
     */
    private $startEndIncQtyValidator;

    /**
     * @var UnitsValidator
     */
    private $unitsValidator;

    /**
     * ProductPuqConfigValidator constructor.
     * @param ReplaceQtyValidator $replaceQtyValidator
     * @param IsQtyDecimalValidator $isQtyDecimalValidator
     * @param QtyTypeValidator $qtyTypeValidator
     * @param UseQuantitiesValidator $useQuantitiesValidator
     * @param StartEndIncQtyValidator $startEndIncQtyValidator
     * @param UnitsValidator $unitsValidator
     */
    public function __construct(
        ReplaceQtyValidator $replaceQtyValidator,
        IsQtyDecimalValidator $isQtyDecimalValidator,
        QtyTypeValidator $qtyTypeValidator,
        UseQuantitiesValidator $useQuantitiesValidator,
        StartEndIncQtyValidator $startEndIncQtyValidator,
        UnitsValidator $unitsValidator
    ) {
        $this->replaceQtyValidator = $replaceQtyValidator;
        $this->isQtyDecimalValidator = $isQtyDecimalValidator;
        $this->qtyTypeValidator = $qtyTypeValidator;
        $this->useQuantitiesValidator = $useQuantitiesValidator;
        $this->startEndIncQtyValidator = $startEndIncQtyValidator;
        $this->unitsValidator = $unitsValidator;
    }

    /**
     * @param RealProductPuqConfigInterface $value
     * @return bool
     * @throws \Zend_Validate_Exception
     */
    public function isValid($value)
    {
        if (!$ret = $this->validateReplaceQty($value->getReplaceQty())) {
            return $ret;
        }

        if (!$ret = $this->validateIsQtyDecimal($value->getIsQtyDecimal())) {
            return $ret;
        }

        $qtyType = $value->getQtyType();

        if (!$ret = $this->validateQtyType($qtyType)) {
            return $ret;
        }

        switch ($qtyType) {
            case QtyTypeInterface::TYPE_STATIC:
                if (!$ret = $this->validateUseQuantities($value)) {
                    return $ret;
                }

                break;
            case QtyTypeInterface::TYPE_DYNAMIC:
                if (!$ret = $this->validateMinMaxIncQty($value)) {
                    return $ret;
                }

                break;
            default:
                throw new \LogicException('Invalid Qty Type value: ' . $qtyType);
        }

        if (!$ret = $this->validateUnits($value)) {
            return $ret;
        }

        return true;
    }

    /**
     * @param int $replaceQty
     * @return bool
     * @throws \Zend_Validate_Exception
     */
    private function validateReplaceQty($replaceQty)
    {
        return $this->validateByValidator($this->replaceQtyValidator, $replaceQty);
    }

    /**
     * @param bool $isQtyDecimal
     * @return bool
     * @throws \Zend_Validate_Exception
     */
    private function validateIsQtyDecimal($isQtyDecimal)
    {
        return $this->validateByValidator($this->isQtyDecimalValidator, $isQtyDecimal);
    }

    /**
     * @param int $qtyType
     * @return bool
     * @throws \Zend_Validate_Exception
     */
    private function validateQtyType($qtyType)
    {
        return $this->validateByValidator($this->qtyTypeValidator, $qtyType);
    }

    /**
     * @param RealProductPuqConfigInterface $puqConfig
     * @return bool
     * @throws \Zend_Validate_Exception
     */
    private function validateUseQuantities(RealProductPuqConfigInterface $puqConfig)
    {
        return $this->validateByValidator($this->useQuantitiesValidator, $puqConfig);
    }

    /**
     * @param RealProductPuqConfigInterface $puqConfig
     * @return bool
     * @throws \Zend_Validate_Exception
     */
    private function validateMinMaxIncQty(RealProductPuqConfigInterface $puqConfig)
    {
        return $this->validateByValidator($this->startEndIncQtyValidator, $puqConfig);
    }

    /**
     * @param RealProductPuqConfigInterface $puqConfig
     * @return bool
     * @throws \Zend_Validate_Exception
     */
    private function validateUnits(RealProductPuqConfigInterface $puqConfig)
    {
        return $this->validateByValidator($this->unitsValidator, $puqConfig);
    }
}
