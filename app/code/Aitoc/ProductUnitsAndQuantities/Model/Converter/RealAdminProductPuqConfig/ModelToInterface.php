<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Model\Converter\RealAdminProductPuqConfig;

use Aitoc\ProductUnitsAndQuantities\Api\Data\RealProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\UseConfigKeyBuilder;
use Aitoc\ProductUnitsAndQuantities\Model\Converter\BaseModelToInterface;
use Aitoc\ProductUnitsAndQuantities\Model\RealProductPuqConfig;
use Aitoc\ProductUnitsAndQuantities\Model\RealProductPuqConfigFactory;

/**
 * Class ModelToInterface
 */
class ModelToInterface extends BaseModelToInterface
{
    /**
     * @var RealProductPuqConfigFactory
     */
    protected $interfaceFactory;

    /**
     * @var UseConfigKeyBuilder
     */
    protected $useConfigKeyBuilder;

    /**
     * ModelToInterface constructor.
     * @param RealProductPuqConfigFactory $interfaceFactory
     * @param UseConfigKeyBuilder $useConfigKeyBuilder
     */
    public function __construct(
        RealProductPuqConfigFactory $interfaceFactory,
        UseConfigKeyBuilder $useConfigKeyBuilder
    ) {
        $this->interfaceFactory = $interfaceFactory;
        $this->useConfigKeyBuilder = $useConfigKeyBuilder;
    }

    /**
     * @return RealProductPuqConfigFactory
     */
    protected function getInterfaceFactory()
    {
        return $this->interfaceFactory;
    }

    /**
     * @param array $modelData
     * @param RealProductPuqConfig $interface
     */
    protected function setModelDataToInterface($modelData, $interface)
    {
        $interface
            ->setId($this->getModelDataByKey($modelData, RealProductPuqConfig::ID))
            ->setProductId($this->getModelDataByKey($modelData, RealProductPuqConfigInterface::PRODUCT_ID))
            ->setReplaceQty($this->getModelDataByKey($modelData, RealProductPuqConfigInterface::REPLACE_QTY))
            ->setStoreId($this->getModelDataByKey($modelData, RealProductPuqConfigInterface::STORE_ID))
            ->setIsQtyDecimal($this->getModelDataByKey($modelData, RealProductPuqConfigInterface::IS_QTY_DECIMAL))
            ->setQtyType($this->getModelDataByKey($modelData, RealProductPuqConfigInterface::QTY_TYPE))
            ->setUseQuantities($this->getModelDataByKey($modelData, RealProductPuqConfigInterface::USE_QUANTITIES))
            ->setStartQty($this->getModelDataByKey($modelData, RealProductPuqConfigInterface::START_QTY))
            ->setQtyIncrement($this->getModelDataByKey($modelData, RealProductPuqConfigInterface::QTY_INCREMENT))
            ->setEndQty($this->getModelDataByKey($modelData, RealProductPuqConfigInterface::END_QTY))
            ->setAllowUnits($this->getModelDataByKey($modelData, RealProductPuqConfigInterface::ALLOW_UNITS))
            ->setPricePer($this->getModelDataByKey($modelData, RealProductPuqConfigInterface::PRICE_PER))
            ->setPricePerDivider($this->getModelDataByKey($modelData, RealProductPuqConfigInterface::PRICE_PER_DIVIDER))

            ->setUseConfigReplaceQty($this->getUseConfigValue($modelData, RealProductPuqConfigInterface::REPLACE_QTY))
            ->setUseConfigIsQtyDecimal(
                $this->getUseConfigValue($modelData, RealProductPuqConfigInterface::IS_QTY_DECIMAL)
            )
            ->setUseConfigQtyType($this->getUseConfigValue($modelData, RealProductPuqConfigInterface::QTY_TYPE))
            ->setUseConfigUseQuantities(
                $this->getUseConfigValue($modelData, RealProductPuqConfigInterface::USE_QUANTITIES)
            )
            ->setUseConfigStartQty($this->getUseConfigValue($modelData, RealProductPuqConfigInterface::START_QTY))
            ->setUseConfigQtyIncrement(
                $this->getUseConfigValue($modelData, RealProductPuqConfigInterface::QTY_INCREMENT)
            )
            ->setUseConfigEndQty($this->getUseConfigValue($modelData, RealProductPuqConfigInterface::END_QTY))
            ->setUseConfigAllowUnits($this->getUseConfigValue($modelData, RealProductPuqConfigInterface::ALLOW_UNITS))
            ->setUseConfigPricePer($this->getUseConfigValue($modelData, RealProductPuqConfigInterface::PRICE_PER))
            ->setUseConfigPricePerDivider(
                $this->getUseConfigValue($modelData, RealProductPuqConfigInterface::PRICE_PER_DIVIDER)
            );
    }

    /**
     * Retrieve model attribute value by key
     * 
     * @param array $modelData
     * @param string $key
     * @return mixed|null
     */
    private function getModelDataByKey($modelData, $key)
    {
        return array_key_exists($key, $modelData) ? $modelData[$key] : null;
    }

    /**
     * @param string $field
     * @return string
     */
    private function getUseConfigKey($field)
    {
        return $this->useConfigKeyBuilder->getUseConfigKey($field);
    }

    /**
     * @param array $modelData
     * @param string $field
     * @return mixed
     */
    private function getUseConfigValue($modelData, $field)
    {
        $key = $this->getUseConfigKey($field);

        if (array_key_exists($key, $modelData)) {
            return $modelData[$key];
        } elseif (array_key_exists(RealProductPuqConfigInterface::USE_CONFIG_PARAMS, $modelData)){
            $useConfigFields = explode(',', $modelData[RealProductPuqConfigInterface::USE_CONFIG_PARAMS]);
            return array_search($field, $useConfigFields);
        }

        return false;
    }
}
