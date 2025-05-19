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

use Aitoc\ProductUnitsAndQuantities\Api\Data\BaseAdminProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PhpTypesInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\UseConfigKeyBuilder;

/**
 * Class BaseProductPuqConfig
 */
class BaseAdminProductPuqConfig extends BaseProductPuqConfig implements BaseAdminProductPuqConfigInterface
{
    const USE_CONFIG_PREFIX = 'use_config_';

    /**
     * @var UseConfigKeyBuilder
     */
    private $useConfigKeyBuilder;

    /**
     * BaseAdminProductPuqConfig constructor.
     * @param array $data
     * @param UseConfigKeyBuilder $useConfigKeyBuilder
     */
    public function __construct(UseConfigKeyBuilder $useConfigKeyBuilder, array $data = [])
    {
        parent::__construct($data);
        $this->useConfigKeyBuilder = $useConfigKeyBuilder;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        $value = $this->_get(self::ID);

        if (!$value) {
            return null;
        }

        return (int) $value;
    }

    /**
     * @inheritdoc
     */
    public function setId($id)
    {
        if (!$id) {
            $id = null;
        } else {
            settype($id, PhpTypesInterface::INT);
        }

        $this->setData(self::ID, $id);
    }

    /**
     * @inheritdoc
     */
    public function getProductId()
    {
        return (int) $this->_get(self::PRODUCT_ID);
    }

    /**
     * @inheritdoc
     */
    public function setProductId($productId)
    {
        $this->setData(self::PRODUCT_ID, (int) $productId);
    }

    /**
     * @inheritDoc
     */
    public function getUseConfigReplaceQty()
    {
        return $this->getUseConfigValueByFieldName(self::REPLACE_QTY);
    }

    /**
     * @inheritDoc
     */
    public function getUseConfigQtyType()
    {
        return $this->getUseConfigValueByFieldName(self::QTY_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function getUseConfigIsQtyDecimal()
    {
        return $this->getUseConfigValueByFieldName(self::IS_QTY_DECIMAL);
    }

    /**
     * @inheritDoc
     */
    public function getUseConfigUseQuantities()
    {
        return $this->getUseConfigValueByFieldName(self::USE_QUANTITIES);
    }

    /**
     * @inheritDoc
     */
    public function getUseConfigStartQty()
    {
        return $this->getUseConfigValueByFieldName(self::START_QTY);
    }

    /**
     * @inheritDoc
     */
    public function getUseConfigQtyIncrement()
    {
        return $this->getUseConfigValueByFieldName(self::QTY_INCREMENT);
    }

    /**
     * @inheritDoc
     */
    public function getUseConfigEndQty()
    {
        return $this->getUseConfigValueByFieldName(self::END_QTY);
    }

    /**
     * @inheritDoc
     */
    public function getUseConfigAllowUnits()
    {
        return $this->getUseConfigValueByFieldName(self::ALLOW_UNITS);
    }

    /**
     * @inheritDoc
     */
    public function getUseConfigPricePer()
    {
        return $this->getUseConfigValueByFieldName(self::PRICE_PER);
    }

    /**
     * @inheritDoc
     */
    public function getUseConfigPricePerDivider()
    {
        return $this->getUseConfigValueByFieldName(self::PRICE_PER_DIVIDER);
    }

    /**
     * @inheritDoc
     */
    public function setUseConfigReplaceQty($value)
    {
        return $this->setUseConfigValueByFieldName(self::REPLACE_QTY, $value);
    }

    /**
     * @inheritDoc
     */
    public function setUseConfigQtyType($value)
    {
        return $this->setUseConfigValueByFieldName(self::QTY_TYPE, $value);
    }

    /**
     * @inheritDoc
     */
    public function setUseConfigIsQtyDecimal($value)
    {
        return $this->setUseConfigValueByFieldName(self::IS_QTY_DECIMAL, $value);
    }

    /**
     * @inheritDoc
     */
    public function setUseConfigUseQuantities($value)
    {
        return $this->setUseConfigValueByFieldName(self::USE_QUANTITIES, $value);
    }

    /**
     * @inheritDoc
     */
    public function setUseConfigStartQty($value)
    {
        return $this->setUseConfigValueByFieldName(self::START_QTY, $value);
    }

    /**
     * @inheritDoc
     */
    public function setUseConfigQtyIncrement($value)
    {
        return $this->setUseConfigValueByFieldName(self::QTY_INCREMENT, $value);
    }

    /**
     * @inheritDoc
     */
    public function setUseConfigEndQty($value)
    {
        return $this->setUseConfigValueByFieldName(self::END_QTY, $value);
    }

    /**
     * @inheritDoc
     */
    public function setUseConfigAllowUnits($value)
    {
        return $this->setUseConfigValueByFieldName(self::ALLOW_UNITS, $value);
    }

    /**
     * @inheritDoc
     */
    public function setUseConfigPricePer($value)
    {
        return $this->setUseConfigValueByFieldName(self::PRICE_PER, $value);
    }

    /**
     * @inheritDoc
     */
    public function setUseConfigPricePerDivider($value)
    {
        return $this->setUseConfigValueByFieldName(self::PRICE_PER_DIVIDER, $value);
    }

    /**
     * @param string $fieldName
     * @return bool
     */
    private function getUseConfigValueByFieldName($fieldName)
    {
        $useConfigFieldName = $this->getUseConfigFieldName($fieldName);

        return (bool) $this->_get($useConfigFieldName);
    }

    /**
     * @param string $fieldName
     * @param bool $value
     * @return BaseAdminProductPuqConfig
     */
    private function setUseConfigValueByFieldName($fieldName, $value)
    {
        $useConfigFieldName = $this->getUseConfigFieldName($fieldName);

        $this->setData($useConfigFieldName, (bool) $value);

        return $this;
    }

    /**
     * @param string $fieldName
     * @return string
     */
    protected function getUseConfigFieldName($fieldName)
    {
        return $this->useConfigKeyBuilder->getUseConfigKey($fieldName);
    }
}
