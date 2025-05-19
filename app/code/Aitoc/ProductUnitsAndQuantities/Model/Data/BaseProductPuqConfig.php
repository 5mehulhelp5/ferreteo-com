<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Model\Data;

use Aitoc\ProductUnitsAndQuantities\Api\Data\ProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PhpTypesInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class BaseProductPuqConfig
 */
class BaseProductPuqConfig extends AbstractSimpleObject implements ProductPuqConfigInterface
{
    /**
     * @return array
     */
    public function toArray()
    {
        return [
            static::REPLACE_QTY => $this->getReplaceQty(),
            static::QTY_TYPE => $this->getQtyType(),
            static::IS_QTY_DECIMAL => $this->getIsQtyDecimal(),
            static::USE_QUANTITIES => $this->getUseQuantities(),
            static::START_QTY => $this->getStartQty(),
            static::QTY_INCREMENT => $this->getQtyIncrement(),
            static::END_QTY => $this->getEndQty(),
            static::ALLOW_UNITS => $this->getAllowUnits(),
            static::PRICE_PER => $this->getPricePer(),
            static::PRICE_PER_DIVIDER => $this->getPricePerDivider()
        ];
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        $value = (int) $this->_get(self::ID);

        if (!$value) {
            return null;
        }

        settype($value, PhpTypesInterface::INT);

        return $value;
    }

    /**
     * @inheritDoc
     */
    public function setId($id)
    {
        return $this->setData(self::ID, (int) $id);
    }

    /**
     * @inheritDoc
     */
    public function getProductId()
    {
        return (int) $this->_get(self::PRODUCT_ID);
    }

    /**
     * @inheritDoc
     */
    public function getReplaceQty()
    {
        return (int) $this->_get(self::REPLACE_QTY);
    }

    /**
     * @inheritDoc
     */
    public function getQtyType()
    {
        return (int) $this->_get(self::QTY_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function getIsQtyDecimal()
    {
        return $this->_get(self::IS_QTY_DECIMAL);
    }

    /**
     * @inheritDoc
     */
    public function getUseQuantities()
    {
        return $this->_get(self::USE_QUANTITIES);
    }

    /**
     * @inheritDoc
     */
    public function getStartQty()
    {
        return (float) $this->_get(self::START_QTY);
    }

    /**
     * @inheritDoc
     */
    public function getQtyIncrement()
    {
        return (float) $this->_get(self::QTY_INCREMENT);
    }

    /**
     * @inheritDoc
     */
    public function getEndQty()
    {
        return (float) $this->_get(self::END_QTY);
    }

    /**
     * @inheritDoc
     */
    public function getAllowUnits()
    {
        return (bool) $this->_get(self::ALLOW_UNITS);
    }

    /**
     * @inheritDoc
     */
    public function getPricePer()
    {
        return $this->_get(self::PRICE_PER);
    }

    /**
     * @inheritDoc
     */
    public function getPricePerDivider()
    {
        return $this->_get(self::PRICE_PER_DIVIDER);
    }

    /**
     * @inheritDoc
     */
    public function setProductId($productId)
    {
        return $this->setData(self::PRODUCT_ID, (int) $productId);
    }

    /**
     * @inheritDoc
     */
    public function setReplaceQty($inputTypeId)
    {
        return $this->setData(self::REPLACE_QTY, (int) $inputTypeId);
    }

    /**
     * @inheritDoc
     */
    public function setQtyType($qtyTypeId)
    {
        return $this->setData(self::QTY_TYPE, (int) $qtyTypeId);
    }

    /**
     * @inheritDoc
     */
    public function setIsQtyDecimal($isQtyDecimal)
    {
        return $this->setData(self::IS_QTY_DECIMAL, (bool) $isQtyDecimal);
    }

    /**
     * @inheritDoc
     */
    public function setUseQuantities($value)
    {
        return $this->setData(self::USE_QUANTITIES, $value);
    }

    /**
     * @inheritDoc
     */
    public function setStartQty($startQty)
    {
        return $this->setData(self::START_QTY, (float) $startQty);
    }

    /**
     * @inheritDoc
     */
    public function setQtyIncrement($qtyIncrement)
    {
        return $this->setData(self::QTY_INCREMENT, (float) $qtyIncrement);
    }

    /**
     * @inheritDoc
     */
    public function setEndQty($endQty)
    {
        return $this->setData(self::END_QTY, (float) $endQty);
    }

    /**
     * @inheritDoc
     */
    public function setAllowUnits($value)
    {
        return $this->setData(self::ALLOW_UNITS, (bool) $value);
    }

    /**
     * @inheritDoc
     */
    public function setPricePer($unit)
    {
        return $this->setData(self::PRICE_PER, $unit);
    }

    /**
     * @inheritDoc
     */
    public function setPricePerDivider($divider)
    {
        return $this->setData(self::PRICE_PER_DIVIDER, $divider);
    }
}
