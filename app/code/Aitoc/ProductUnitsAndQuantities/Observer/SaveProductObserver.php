<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Observer;

use Aitoc\ProductUnitsAndQuantities\Api\Data\ProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\RealProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\AclInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\SystemPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Helper\PuqConfig\Mapper\WithUseConfigMapperInterface;
use Aitoc\ProductUnitsAndQuantities\Api\PuqConfigurationInterface;
use Aitoc\ProductUnitsAndQuantities\Api\RealProductPuqConfigRepositoryInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\Data;
use Aitoc\ProductUnitsAndQuantities\Helper\ResultConfigBuilder;
use Aitoc\ProductUnitsAndQuantities\Model\RealProductPuqConfig;
use Aitoc\ProductUnitsAndQuantities\Model\RealProductPuqConfigFactory;
use Aitoc\ProductUnitsAndQuantities\Model\ResultAdminProductPuqConfigSaver;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\ValidatorException;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Zend_Validate_Exception;

/**
 * Class SaveProductObserver
 */
class SaveProductObserver implements ObserverInterface
{
    /**
     * @var ResultConfigBuilder
     */
    protected $resultConfigBuilder;
    /**
     * @var Data
     */
    private $helper;
    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    /**
     * @var RealProductPuqConfigRepositoryInterface
     */
    private $realProductPuqConfigRepository;
    /**
     * @var RealProductPuqConfigFactory
     */
    private $realAdminProductPuqConfigFactory;

    /**
     * @var PuqConfigurationInterface
     */
    private $puqConfiguration;
    /**
     * @var WithUseConfigMapperInterface
     */
    private $withUseConfigMapper;
    /**
     * @var ResultAdminProductPuqConfigSaver
     */
    private $resultAdminProductPuqConfigSaver;

    /** @var StoreManagerInterface */
    private $storeManager;

    /**
     * SaveProductObserver constructor.
     * @param Data $helper
     * @param AuthorizationInterface $authorization
     * @param RealProductPuqConfigRepositoryInterface $realProductPuqConfigRepository
     * @param RealProductPuqConfigFactory $realAdminProductPuqConfigFactory
     * @param PuqConfigurationInterface $puqConfiguration
     * @param WithUseConfigMapperInterface $withUseConfigMapper
     * @param ResultAdminProductPuqConfigSaver $resultAdminProductPuqConfigSaver
     * @param ResultConfigBuilder $resultConfigBuilder
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Data $helper,
        AuthorizationInterface $authorization,
        RealProductPuqConfigRepositoryInterface $realProductPuqConfigRepository,
        RealProductPuqConfigFactory $realAdminProductPuqConfigFactory,
        PuqConfigurationInterface $puqConfiguration,
        WithUseConfigMapperInterface $withUseConfigMapper,
        ResultAdminProductPuqConfigSaver $resultAdminProductPuqConfigSaver,
        ResultConfigBuilder $resultConfigBuilder,
        StoreManagerInterface $storeManager
    ) {
        $this->helper = $helper;
        $this->authorization = $authorization;
        $this->realProductPuqConfigRepository = $realProductPuqConfigRepository;
        $this->realAdminProductPuqConfigFactory = $realAdminProductPuqConfigFactory;
        $this->puqConfiguration = $puqConfiguration;
        $this->withUseConfigMapper = $withUseConfigMapper;
        $this->resultAdminProductPuqConfigSaver = $resultAdminProductPuqConfigSaver;
        $this->resultConfigBuilder = $resultConfigBuilder;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     * @param Observer $observer
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     * @throws ValidatorException
     * @throws Zend_Validate_Exception
     */
    public function execute(Observer $observer)
    {
        if (!$this->isAllowedForCurrentUser()) {
            return;
        }

        $product = $this->getProductByObserver($observer);

        if (!$this->isApplicableForProduct($product)) {
            return;
        }

        $storeId = $this->getCurrentStoreId();
        $productPuqConfig = $this->productToResultAdminPuqConfig($product, $storeId);
        $this->saveResultPuqConfig($productPuqConfig);
    }

    /**
     * @return bool
     */
    private function isAllowedForCurrentUser()
    {
        return $this->authorization->isAllowed(AclInterface::DEFAULT_VALUE);
    }

    /**
     * @param Observer $observer
     * @return mixed
     */
    private function getProductByObserver(Observer $observer)
    {
        return $observer->getEvent()->getProduct();
    }

    /**
     * @param ProductInterface $product
     * @return bool
     */
    private function isApplicableForProduct(ProductInterface $product)
    {
        return $this->helper->isApplicableForProduct($product);
    }

    /**
     * @return int
     * @throws NoSuchEntityException
     */
    private function getCurrentStoreId()
    {
        return $this->getCurrentStore()->getId();
    }

    /**
     * @return StoreInterface
     * @throws NoSuchEntityException
     */
    private function getCurrentStore()
    {
        return $this->storeManager->getStore();
    }

    /**
     * @param ProductInterface $product
     * @param int $storeId
     * @return ProductPuqConfigInterface
     */
    private function productToResultAdminPuqConfig(ProductInterface $product, $storeId)
    {
        $sourceResultAdminPuqConfig = $this->getOrCreatePuqConfigByProductId($product->getId(), $storeId);
        $inputPuqConfig = $this->productToAdminPuqConfig($product, $storeId);
        $systemPuqConfig = $this->getSystemPuqConfig();

        return $this->getResultPuqConfig($sourceResultAdminPuqConfig, $inputPuqConfig, $systemPuqConfig);
    }

    /**
     * @param int $productId
     * @param int $storeId
     * @return RealProductPuqConfig|null
     */
    private function getOrCreatePuqConfigByProductId($productId, $storeId)
    {
        $productPuqConfig = $this->getRealProductPuqConfig($productId, $storeId);

        if ($storeId && !$productPuqConfig) {
            $productPuqConfig = $this->getRealProductPuqConfig($productId, Store::DEFAULT_STORE_ID);

            if ($productPuqConfig) {
                $productPuqConfig->setId(null);
            }
        }

        if (!$productPuqConfig) {
            $productPuqConfig = $this->createPuqConfig();
        }

        return $productPuqConfig;
    }

    /**
     * @param int $productId
     * @param int $storeId
     * @return RealProductPuqConfig|null
     */
    private function getRealProductPuqConfig($productId, $storeId)
    {
        return $this->realProductPuqConfigRepository->getByProductIdAndStoreId($productId, $storeId);
    }

    /**
     * @return RealProductPuqConfig
     */
    private function createPuqConfig()
    {
        return $this->realAdminProductPuqConfigFactory->create();
    }

    /**
     * @param ProductInterface $product
     * @param int $storeId
     * @return RealProductPuqConfig|null
     */
    private function productToAdminPuqConfig(ProductInterface $product, $storeId)
    {
        $puqProductConfig = $this->createPuqConfig();

        $this->withUseConfigMapper->mapNotNullValues($product, $puqProductConfig);
        $this->withUseConfigMapper->mapNotNullUseConfigValues($product, $puqProductConfig);

        $puqProductConfig
            ->setProductId($product->getId())
            ->setStoreId($storeId)
        ;

        return $puqProductConfig;
    }

    /**
     * @return SystemPuqConfigInterface
     */
    private function getSystemPuqConfig()
    {
        return $this->puqConfiguration->getRealPuqSystemConfig();
    }

    /**
     * @param RealProductPuqConfigInterface $sourceRealPuqConfig
     * @param RealProductPuqConfigInterface $inputPuqConfig
     * @param SystemPuqConfigInterface $systemPuqConfig
     * @return ProductPuqConfigInterface
     */
    private function getResultPuqConfig(
        RealProductPuqConfigInterface $sourceRealPuqConfig,
        RealProductPuqConfigInterface $inputPuqConfig,
        SystemPuqConfigInterface $systemPuqConfig
    ) {
        return $this->resultConfigBuilder->getResultPuqConfig($sourceRealPuqConfig, $inputPuqConfig, $systemPuqConfig);
    }

    /**
     * @param $productPuqConfig
     * @throws CouldNotSaveException
     * @throws ValidatorException
     * @throws Zend_Validate_Exception
     */
    private function saveResultPuqConfig($productPuqConfig)
    {
        $this->resultAdminProductPuqConfigSaver->save($productPuqConfig);
    }
}
