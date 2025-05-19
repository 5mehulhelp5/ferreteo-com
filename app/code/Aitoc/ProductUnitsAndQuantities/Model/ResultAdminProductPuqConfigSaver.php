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

use Aitoc\ProductUnitsAndQuantities\Api\Data\ProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\Validator\ProductPuqConfigValidator;
use Magento\Framework\Exception\ValidatorException;
use Zend_Validate_Exception;
use Aitoc\ProductUnitsAndQuantities\Model\Data\ResultAdminProductPuqConfig;
use Aitoc\ProductUnitsAndQuantities\Api\RealProductPuqConfigRepositoryInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\RealProductPuqConfigInterface;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * Class ResultAdminProductPuqConfigSaver
 */
class ResultAdminProductPuqConfigSaver
{
    /**
     * @var ProductPuqConfigValidator
     */
    private $productPuqConfigValidator;

    /**
     * @var RealProductPuqConfigRepositoryInterface
     */
    private $realAdminProductPuqConfigRepository;

    /**
     * ResultAdminProductPuqConfigSaver constructor.
     * @param ProductPuqConfigValidator $productPuqConfigValidator
     * @param RealProductPuqConfigRepositoryInterface $realProductPuqConfigRepository
     */
    public function __construct(
        ProductPuqConfigValidator $productPuqConfigValidator,
        RealProductPuqConfigRepositoryInterface $realProductPuqConfigRepository
    ) {
        $this->productPuqConfigValidator = $productPuqConfigValidator;
        $this->realAdminProductPuqConfigRepository = $realProductPuqConfigRepository;
    }

    /**
     * @param ProductPuqConfigInterface $productPuqConfig
     * @throws ValidatorException
     * @throws Zend_Validate_Exception
     * @throws CouldNotSaveException
     */
    public function save(ProductPuqConfigInterface $productPuqConfig)
    {
        /*
         * If no real entity and have all use_config - not save
         */
        $this->validateAdminProductPuqConfig($productPuqConfig);

        if ($this->shouldBeDeleted($productPuqConfig)) {
            $this->deleteAdminProductPuqConfig($productPuqConfig);
        } elseif ($this->shouldBeSaved($productPuqConfig)) {
            $this->saveAdminProductPuqConfig($productPuqConfig);
        }
    }

    /**
     * @param RealProductPuqConfigInterface $baseAdminPuqConfig
     * @throws CouldNotSaveException
     */
    private function saveAdminProductPuqConfig(RealProductPuqConfigInterface $baseAdminPuqConfig)
    {
        $this->realAdminProductPuqConfigRepository->save($baseAdminPuqConfig);
    }


    /**
     * @param RealProductPuqConfig $adminProductPuqConfig
     * @return bool
     */
    private function shouldBeSaved($adminProductPuqConfig)
    {
        if ($this->hasNotUseConfigOrNotDefaultValues($adminProductPuqConfig)
            || $adminProductPuqConfig->getStoreId()
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param ProductPuqConfigInterface $productPuqConfig
     * @throws ValidatorException
     * @throws Zend_Validate_Exception
     */
    private function validateAdminProductPuqConfig($productPuqConfig)
    {
        if (!$this->productPuqConfigValidator->isValid($productPuqConfig)) {
            $validationMessages = $this->productPuqConfigValidator->getMessages();
            throw new ValidatorException(current($validationMessages));
            //todo: implement by some example
        }
    }

    /**
     * @param ResultAdminProductPuqConfig $adminProductPuqConfig
     * @return bool
     */
    private function shouldBeDeleted($adminProductPuqConfig)
    {
        return ($this->isExistsAdminProductPuqConfig($adminProductPuqConfig)
            && !$this->hasNotUseConfigOrNotDefaultValues($adminProductPuqConfig)
            && !$adminProductPuqConfig->getStoreId());
    }
    /**
     * @param RealProductPuqConfig $adminProductPuqConfig
     * @return bool
     */
    private function isExistsAdminProductPuqConfig($adminProductPuqConfig)
    {
        return $adminProductPuqConfig->getId() > 0;
    }

    /**
     * @param RealProductPuqConfig $adminProductPuqConfig
     * @return bool
     */
    private function hasNotUseConfigOrNotDefaultValues($adminProductPuqConfig)
    {
        return !(
            $adminProductPuqConfig->getUseConfigReplaceQty()
            && $adminProductPuqConfig->getUseConfigIsQtyDecimal()
            && $adminProductPuqConfig->getUseConfigQtyType()
            && $adminProductPuqConfig->getUseConfigUseQuantities()
            && $adminProductPuqConfig->getUseConfigStartQty()
            && $adminProductPuqConfig->getUseConfigQtyIncrement()
            && $adminProductPuqConfig->getUseConfigEndQty()
            && $adminProductPuqConfig->getUseConfigAllowUnits()
            && $adminProductPuqConfig->getUseConfigPricePer()
            && $adminProductPuqConfig->getUseConfigPricePerDivider()
        );
    }

    /**
     * @param RealProductPuqConfigInterface $baseAdminPuqConfig
     */
    private function deleteAdminProductPuqConfig(RealProductPuqConfigInterface $baseAdminPuqConfig)
    {
        $this->realAdminProductPuqConfigRepository->delete($baseAdminPuqConfig);
    }
}
