<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Model\Config\Backend;

use Aitoc\ProductUnitsAndQuantities\Helper\Validator\ProductPuqConfig\UseQuantitiesValidator;
use Aitoc\ProductUnitsAndQuantities\Model\Data\BaseProductPuqConfig;
use Aitoc\ProductUnitsAndQuantities\Model\Data\BaseProductPuqConfigFactory;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\Initial as InitialConfig;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Phrase;
use Magento\Framework\Registry;

/**
 * Class UseQuantitiesValidate
 */
class UseQuantities extends Value
{
    /**
     * @var UseQuantitiesValidator
     */
    private $useQuantitiesValidator;

    /**
     * @var BaseProductPuqConfigFactory
     */
    private $productPuqConfigFactory;

    /**
     * @var InitialConfig
     */
    private $initialConfig;

    /**
     * UseQuantities constructor.
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param UseQuantitiesValidator $useQuantitiesValidator
     * @param BaseProductPuqConfigFactory $productPuqConfigFactory
     * @param InitialConfig $initialConfig
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        UseQuantitiesValidator $useQuantitiesValidator,
        BaseProductPuqConfigFactory $productPuqConfigFactory,
        InitialConfig $initialConfig,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->useQuantitiesValidator = $useQuantitiesValidator;
        $this->productPuqConfigFactory = $productPuqConfigFactory;
        $this->initialConfig = $initialConfig;

        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    /**
     * @return $this|void
     * @throws LocalizedException
     */
    public function beforeSave()
    {
        $value = $this->getValue();
        $this->validateUseQuantities($value);
    }

    /**
     * @param string $valueStr
     * @throws LocalizedException
     */
    private function validateUseQuantities($valueStr)
    {
        $productPuqConfig = $this->getProductPuqConfigWithResultValues($valueStr);

        if (!$this->isValid($productPuqConfig)) {
            throw new LocalizedException($this->getValidationMessage());
        }
    }

    /**
     * @return Phrase
     */
    private function getValidationMessage()
    {
        return current($this->useQuantitiesValidator->getMessages());
    }

    /**
     * @param BaseProductPuqConfig $productPuqConfig
     * @return bool
     */
    private function isValid(BaseProductPuqConfig $productPuqConfig)
    {
        return $this->useQuantitiesValidator->isValid($productPuqConfig);
    }

    /**
     * @param string $valueStr
     * @return BaseProductPuqConfig
     */
    private function getProductPuqConfigWithResultValues($valueStr)
    {
        if (!$valueStr) {
            $valueStr = $this->getConfigFieldValue('use_quantities');
        }

        $replaceQty = $this->getResultReplaceQtyValue();
        $isQtyDecimal = $this->getResultIsQtyDecimalValue();

        return $this->getProductPuqConfig($replaceQty, $isQtyDecimal, $valueStr);
    }

    /**
     * @return int
     */
    private function getResultReplaceQtyValue()
    {
        return (int) $this->getResultFieldValue('replace_qty');
    }

    /**
     * @return bool
     */
    private function getResultIsQtyDecimalValue()
    {
        return (bool) $this->getResultFieldValue('is_qty_decimal');
    }

    /**
     * @param int $replaceQty
     * @param bool $isQtyDecimal
     * @param string $useQuantities
     * @return BaseProductPuqConfig
     */
    private function getProductPuqConfig($replaceQty, $isQtyDecimal, $useQuantities)
    {
        $productPuqConfig = $this->productPuqConfigFactory->create();

        $productPuqConfig
            ->setReplaceQty($replaceQty)
            ->setUseQuantities($useQuantities)
            ->setIsQtyDecimal($isQtyDecimal);

        return $productPuqConfig;
    }

    /**
     * @param string $fieldName
     * @return string
     */
    private function getResultFieldValue($fieldName)
    {
        $value = $this->getFieldsetDataValue($fieldName);

        if ($value === null) {
            $value = $this->getConfigFieldValue($fieldName);
        }

        return $value;
    }

    /**
     * @param string $fieldName
     * @return mixed
     */
    private function getConfigFieldValue($fieldName)
    {
        $initialConfig = $this->initialConfig->getData(ScopeConfigInterface::SCOPE_TYPE_DEFAULT);

        return $initialConfig['product_units_and_quantities']['general_settings'][$fieldName];
    }
}
