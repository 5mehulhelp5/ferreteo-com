<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Helper;

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PhpTypesInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Helper\PuqConfig\Mapper\PuqConfigMapperInterface;
use Aitoc\ProductUnitsAndQuantities\Api\PuqConfigurationInterface;
use Aitoc\ProductUnitsAndQuantities\Model\Data\SystemPuqConfig;
use Aitoc\ProductUnitsAndQuantities\Model\Data\SystemPuqConfigFactory;
use InvalidArgumentException;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class PuqSystemConfigHelper
 */
class RealPuqSystemConfiguration implements PuqConfigurationInterface
{
    const CONFIG_PREFIX = 'product_units_and_quantities/general_settings/';
    const TYPES_MAP = [
        self::REPLACE_QTY => PhpTypesInterface::INT,
        self::QTY_TYPE => PhpTypesInterface::INT,
        self::IS_QTY_DECIMAL => PhpTypesInterface::BOOL,
        self::USE_QUANTITIES => PhpTypesInterface::STRING,
        self::START_QTY => PhpTypesInterface::FLOAT,
        self::QTY_INCREMENT => PhpTypesInterface::FLOAT,
        self::END_QTY => PhpTypesInterface::FLOAT,

        self::ALLOW_UNITS => PhpTypesInterface::BOOL,
        self::PRICE_PER => PhpTypesInterface::STRING,
        self::PRICE_PER_DIVIDER => PhpTypesInterface::STRING,
    ];
    /**
     * @var SystemPuqConfigFactory
     */
    private $systemPuqConfigFactory;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var PuqConfigMapperInterface
     */
    private $puqConfigMapper;

    /**
     * SystemPuqConfigHelper constructor.
     *
     * @param SystemPuqConfigFactory $systemPuqConfigFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param PuqConfigMapperInterface $puqConfigMapper
     */
    public function __construct(
        SystemPuqConfigFactory $systemPuqConfigFactory,
        ScopeConfigInterface $scopeConfig,
        PuqConfigMapperInterface $puqConfigMapper
    ) {
        $this->systemPuqConfigFactory = $systemPuqConfigFactory;
        $this->scopeConfig = $scopeConfig;
        $this->puqConfigMapper = $puqConfigMapper;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getValue($name)
    {
        $puqSystemConfig = $this->getRealPuqSystemConfig();
        $puqSystemConfigArray = $puqSystemConfig->toArray();

        return $puqSystemConfigArray[$name];
    }

    /**
     * @inheritdoc
     */
    public function getRealPuqSystemConfig()
    {
        /** @var SystemPuqConfig $puqSystemConfig */
        $puqSystemConfig = $this->systemPuqConfigFactory->create();

        $this->puqConfigMapper->mapPuqConfigWithoutUseConfig($this, $puqSystemConfig);

        return $puqSystemConfig;
    }

    /**
     * @inheritdoc
     */
    public function getReplaceQty()
    {
        return $this->getPuqConfigByKey(static::REPLACE_QTY);
    }

    /**
     * @param string $key
     * @return mixed
     */
    private function getPuqConfigByKey($key)
    {
        $type = $this->getTypeByKey($key);

        $fullConfigKey = $this->getFullConfigKey($key);

        $value = $this->scopeConfig->getValue($fullConfigKey, ScopeInterface::SCOPE_STORE);

        if ($type) {
            settype($value, $type);
        } elseif (is_numeric($value)) {
            $value += 0;
        }

        return $value;
    }

    /**
     * @param string $key
     * @return string
     */
    private function getTypeByKey($key)
    {
        if (!isset(self::TYPES_MAP[$key])) {
            throw new InvalidArgumentException('Invalid key value: ' . $key);
        }

        return self::TYPES_MAP[$key];
    }

    /**
     * @param string $key
     * @return string
     */
    private function getFullConfigKey($key)
    {
        return self::CONFIG_PREFIX . $key;
    }

    /**
     * @inheritdoc
     */
    public function getQtyType()
    {
        return $this->getPuqConfigByKey(static::QTY_TYPE);
    }

    /**
     * @inheritdoc
     */
    public function getIsQtyDecimal()
    {
        return $this->getPuqConfigByKey(static::IS_QTY_DECIMAL);
    }

    /**
     * @inheritdoc
     */
    public function getUseQuantities()
    {
        return $this->getPuqConfigByKey(static::USE_QUANTITIES);
    }

    /**
     * @inheritdoc
     */
    public function getStartQty()
    {
        return $this->getPuqConfigByKey(static::START_QTY);
    }

    /**
     * @inheritdoc
     */
    public function getQtyIncrement()
    {
        return $this->getPuqConfigByKey(static::QTY_INCREMENT);
    }

    /**
     * @inheritdoc
     */
    public function getEndQty()
    {
        return $this->getPuqConfigByKey(static::END_QTY);
    }

    /**
     * @inheritdoc
     */
    public function getAllowUnits()
    {
        return $this->getPuqConfigByKey(static::ALLOW_UNITS);
    }

    /**
     * @inheritdoc
     */
    public function getPricePer()
    {
        return $this->getPuqConfigByKey(static::PRICE_PER);
    }

    /**
     * @inheritdoc
     */
    public function getPricePerDivider()
    {
        return $this->getPuqConfigByKey(static::PRICE_PER_DIVIDER);
    }
}
