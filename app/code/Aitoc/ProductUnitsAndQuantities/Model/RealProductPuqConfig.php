<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Model;

use Aitoc\ProductUnitsAndQuantities\Api\Data\RealProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PhpTypesInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\UseConfigKeyBuilder;
use Aitoc\ProductUnitsAndQuantities\Model\ResourceModel\RealProductPuqConfig as AdminProductPuqConfigResourceModel;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

/**
 * Class AdminProductPuqConfig
 */
class RealProductPuqConfig extends AbstractModel implements RealProductPuqConfigInterface
{
    const USE_CONFIG = 'use_config';
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'aitoc_productunitsandquantities_admin_product_puq_config';
    /**
     * @var UseConfigKeyBuilder
     */
    private $useConfigKeyBuilder;

    /**
     * RealAdminProductPuqConfig constructor.
     * @param UseConfigKeyBuilder $useConfigKeyBuilder
     * @param Context $context
     * @param Registry $registry
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        UseConfigKeyBuilder $useConfigKeyBuilder,
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->useConfigKeyBuilder = $useConfigKeyBuilder;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @inheritDoc
     */
    public function getUseConfigReplaceQty()
    {
        return $this->getUseConfigFieldByKey(self::REPLACE_QTY);
    }

    /**
     * @param string $key
     * @return bool
     */
    private function getUseConfigFieldByKey($key)
    {
        $useConfigKey = $this->getUseConfigKey($key);
        $result = $this->getData($useConfigKey);

        return ($result !== null) ? (bool) $result : $result;
    }

    /**
     * @param string $key
     * @return string
     */
    private function getUseConfigKey($key)
    {
        return $this->useConfigKeyBuilder->getUseConfigKey($key);
    }

    /**
     * @inheritDoc
     */
    public function setUseConfigReplaceQty($inputTypeId)
    {
        return $this->setUseConfigFieldByKey(self::REPLACE_QTY, $inputTypeId);
    }

    /**
     * @param string $key
     * @param string $value
     * @return $this
     */
    private function setUseConfigFieldByKey($key, $value)
    {
        $useConfigKey = $this->getUseConfigKey($key);

        return $this->setData($useConfigKey, (bool) $value);
    }

    /**
     * @inheritDoc
     */
    public function getUseConfigQtyType()
    {
        return $this->getUseConfigFieldByKey(self::QTY_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setUseConfigQtyType($qtyTypeId)
    {
        return $this->setUseConfigFieldByKey(self::QTY_TYPE, $qtyTypeId);
    }

    /**
     * @inheritDoc
     */
    public function getUseConfigIsQtyDecimal()
    {
        return $this->getUseConfigFieldByKey(self::IS_QTY_DECIMAL);
    }

    /**
     * @inheritDoc
     */
    public function setUseConfigIsQtyDecimal($isQtyDecimal)
    {
        return $this->setUseConfigFieldByKey(self::IS_QTY_DECIMAL, $isQtyDecimal);
    }

    /**
     * @inheritDoc
     */
    public function getUseConfigUseQuantities()
    {
        return $this->getUseConfigFieldByKey(self::USE_QUANTITIES);
    }

    /**
     * @inheritDoc
     */
    public function setUseConfigUseQuantities($value)
    {
        return $this->setUseConfigFieldByKey(self::USE_QUANTITIES, $value);
    }

    /**
     * @inheritDoc
     */
    public function getUseConfigStartQty()
    {
        return $this->getUseConfigFieldByKey(self::START_QTY);
    }

    /**
     * @inheritDoc
     */
    public function setUseConfigStartQty($value)
    {
        return $this->setUseConfigFieldByKey(self::START_QTY, $value);
    }

    /**
     * @inheritDoc
     */
    public function getUseConfigQtyIncrement()
    {
        return $this->getUseConfigFieldByKey(self::QTY_INCREMENT);
    }

    /**
     * @inheritDoc
     */
    public function setUseConfigQtyIncrement($value)
    {
        return $this->setUseConfigFieldByKey(self::QTY_INCREMENT, $value);
    }

    /**
     * @inheritDoc
     */
    public function getUseConfigEndQty()
    {
        return $this->getUseConfigFieldByKey(self::END_QTY);
    }

    /**
     * @inheritDoc
     */
    public function setUseConfigEndQty($value)
    {
        return $this->setUseConfigFieldByKey(self::END_QTY, $value);
    }

    /**
     * @inheritDoc
     */
    public function getUseConfigAllowUnits()
    {
        return $this->getUseConfigFieldByKey(self::ALLOW_UNITS);
    }

    /**
     * @inheritDoc
     */
    public function setUseConfigAllowUnits($value)
    {
        return $this->setUseConfigFieldByKey(self::ALLOW_UNITS, $value);
    }

    /**
     * @inheritDoc
     */
    public function getUseConfigPricePer()
    {
        return $this->getUseConfigFieldByKey(self::PRICE_PER);
    }

    /**
     * @inheritDoc
     */
    public function setUseConfigPricePer($value)
    {
        return $this->setUseConfigFieldByKey(self::PRICE_PER, $value);
    }

    /**
     * @inheritDoc
     */
    public function getUseConfigPricePerDivider()
    {
        return $this->getUseConfigFieldByKey(self::PRICE_PER_DIVIDER);
    }

    /**
     * @inheritDoc
     */
    public function setUseConfigPricePerDivider($value)
    {
        return $this->setUseConfigFieldByKey(self::PRICE_PER_DIVIDER, $value);
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        $value = $this->getData(self::ID);

        if ($value) {
            settype($value, PhpTypesInterface::INT);
        }

        return $value;
    }

    /**
     * @inheritDoc
     */
    public function setId($id)
    {
        return $this->setTypedDataOrNull(self::ID, $id, 'int');
    }

    /**
     * @inheritDoc
     */
    public function getProductId()
    {
        return $this->getTypedDataOrNull(self::PRODUCT_ID, 'int');
    }

    private function getTypedDataOrNull($key, $type)
    {
        $result = $this->getData($key);

        if ($result !== null) {
            settype($result, $type);
        }

        return $result;
    }

    private function setTypedDataOrNull($key, $value, $type)
    {
        if ($value !== null) {
            settype($value, $type);
        }

        $this->setData($key, $value);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setProductId($productId)
    {
        return $this->setTypedDataOrNull(self::PRODUCT_ID, $productId, 'int');
    }

    /**
     * @inheritDoc
     */
    public function getStoreId()
    {
        return $this->getTypedDataOrNull(self::STORE_ID, 'int');
    }

    /**
     * @inheritDoc
     */
    public function setStoreId($storeId)
    {
        return $this->setTypedDataOrNull(self::STORE_ID, $storeId, 'int');
    }

    /**
     * @inheritDoc
     */
    public function getReplaceQty()
    {
        return $this->getTypedDataOrNull(self::REPLACE_QTY, 'int');
    }

    /**
     * @inheritDoc
     */
    public function getQtyType()
    {
        return $this->getTypedDataOrNull(self::QTY_TYPE, 'int');
    }

    /**
     * @inheritDoc
     */
    public function getUseQuantities()
    {
        //todo: return and set array
        return $this->getTypedDataOrNull(self::USE_QUANTITIES, 'string');
    }

    /**
     * @inheritDoc
     */
    public function getIsQtyDecimal()
    {
        return $this->getTypedDataOrNull(self::IS_QTY_DECIMAL, 'bool');
    }

    /**
     * @inheritDoc
     */
    public function getStartQty()
    {
        return $this->getTypedDataOrNull(self::START_QTY, 'float');
    }

    /**
     * @inheritDoc
     */
    public function getQtyIncrement()
    {
        return $this->getTypedDataOrNull(self::QTY_INCREMENT, 'float');
    }

    /**
     * @inheritDoc
     */
    public function getEndQty()
    {
        return $this->getTypedDataOrNull(self::END_QTY, 'float');
    }

    /**
     * @inheritDoc
     */
    public function getAllowUnits()
    {
        return $this->getTypedDataOrNull(self::ALLOW_UNITS, 'bool');
    }

    /**
     * @inheritDoc
     */
    public function getPricePer()
    {
        return $this->getTypedDataOrNull(self::PRICE_PER, 'string');
    }

    /**
     * @inheritDoc
     */
    public function getPricePerDivider()
    {
        return $this->getTypedDataOrNull(self::PRICE_PER_DIVIDER, 'string');
    }

    /**
     * @inheritDoc
     */
    public function setReplaceQty($inputTypeId)
    {
        return $this->setTypedDataOrNull(self::REPLACE_QTY, $inputTypeId, 'int');
    }

    /**
     * @inheritDoc
     */
    public function setQtyType($qtyTypeId)
    {
        return $this->setTypedDataOrNull(self::QTY_TYPE, $qtyTypeId, 'int');
    }

    /**
     * @inheritDoc
     */
    public function setIsQtyDecimal($isQtyDecimal)
    {
        return $this->setTypedDataOrNull(self::IS_QTY_DECIMAL, $isQtyDecimal, 'bool');
    }

    /**
     * @inheritDoc
     */
    public function setUseQuantities($value)
    {
        //todo: set and get as (sorted) array
        return $this->setTypedDataOrNull(self::USE_QUANTITIES, $value, 'string');
    }

    /**
     * @inheritDoc
     */
    public function setStartQty($startQty)
    {
        return $this->setTypedDataOrNull(self::START_QTY, $startQty, 'float');
    }

    /**
     * @inheritDoc
     */
    public function setQtyIncrement($qtyIncrement)
    {
        return $this->setTypedDataOrNull(self::QTY_INCREMENT, $qtyIncrement, 'float');
    }

    /**
     * @inheritDoc
     */
    public function setEndQty($endQty)
    {
        return $this->setTypedDataOrNull(self::END_QTY, $endQty, 'float');
    }

    /**
     * @inheritDoc
     */
    public function setAllowUnits($value)
    {
        return $this->setTypedDataOrNull(self::ALLOW_UNITS, $value, 'bool');
    }

    /**
     * @inheritDoc
     */
    public function setPricePer($unit)
    {
        return $this->setTypedDataOrNull(self::PRICE_PER, $unit, 'string');
    }

    /**
     * @inheritDoc
     */
    public function setPricePerDivider($divider)
    {
        return $this->setTypedDataOrNull(self::PRICE_PER_DIVIDER, $divider, 'string');
    }

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(AdminProductPuqConfigResourceModel::class);
    }
}
