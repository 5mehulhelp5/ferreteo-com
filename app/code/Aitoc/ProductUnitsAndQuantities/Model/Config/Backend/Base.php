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

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfigFieldIdsInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\Validator\ProductPuqConfig\StartEndIncQty\Base as BaseValidator;
use Aitoc\ProductUnitsAndQuantities\Model\Data\SystemPuqConfig;
use Aitoc\ProductUnitsAndQuantities\Model\Data\SystemPuqConfigFactory;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Zend_Validate_Exception;

/**
 * Class Base
 */
class Base extends Value
{
    /**
     * @var BaseValidator
     */
    private $validator;

    /**
     * @var SystemPuqConfigFactory
     */
    private $systemPuqConfigFactory;

    /**
     * StartQty constructor.
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param SystemPuqConfigFactory $systemPuqConfigFactory
     * @param BaseValidator $validator
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        SystemPuqConfigFactory $systemPuqConfigFactory,
        BaseValidator $validator,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);

        $this->systemPuqConfigFactory = $systemPuqConfigFactory;
        $this->validator = $validator;
    }

    /**
     * @return $this
     * @throws LocalizedException
     * @throws Zend_Validate_Exception
     */
    public function beforeSave()
    {
        $puqConfig = $this->getPuqConfig();
        $this->validateFieldValue($puqConfig);

        return parent::beforeSave();
    }

    /**
     * @return SystemPuqConfig
     */
    private function getPuqConfig()
    {
        $systemPuqConfig = $this->systemPuqConfigFactory->create();
        $systemPuqConfig
            ->setReplaceQty($this->getFieldsetDataValue(PuqConfigFieldIdsInterface::REPLACE_QTY))
            ->setIsQtyDecimal($this->getFieldsetDataValue(PuqConfigFieldIdsInterface::IS_QTY_DECIMAL))
            ->setQtyType($this->getFieldsetDataValue(PuqConfigFieldIdsInterface::QTY_TYPE))
            ->setStartQty($this->getFieldsetDataValue(PuqConfigFieldIdsInterface::START_QTY))
            ->setQtyIncrement($this->getFieldsetDataValue(PuqConfigFieldIdsInterface::QTY_INCREMENT))
            ->setEndQty($this->getFieldsetDataValue(PuqConfigFieldIdsInterface::END_QTY))
            ->setUseQuantities($this->getFieldsetDataValue(PuqConfigFieldIdsInterface::USE_QUANTITIES))
            ->setAllowUnits($this->getFieldsetDataValue(PuqConfigFieldIdsInterface::ALLOW_UNITS))
            ->setPricePer($this->getFieldsetDataValue(PuqConfigFieldIdsInterface::PRICE_PER))
            ->setPricePerDivider($this->getFieldsetDataValue(PuqConfigFieldIdsInterface::PRICE_PER_DIVIDER));

        return $systemPuqConfig;
    }

    /**
     * @param SystemPuqConfig $puqConfig
     * @return void
     * @throws LocalizedException
     * @throws Zend_Validate_Exception
     */
    private function validateFieldValue(SystemPuqConfig $puqConfig)
    {
        if (!$this->validator->isValid($puqConfig)) {
            $messages = $this->validator->getMessages();
            $message = current($messages);

            throw new LocalizedException($message);
        }
    }
}
