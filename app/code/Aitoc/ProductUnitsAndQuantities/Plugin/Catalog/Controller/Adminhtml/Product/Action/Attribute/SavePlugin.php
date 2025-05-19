<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Plugin\Catalog\Controller\Adminhtml\Product\Action\Attribute;

use Aitoc\ProductUnitsAndQuantities\Api\Data\ProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\RealProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\AclInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\SystemPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\PuqConfigurationInterface;
use Aitoc\ProductUnitsAndQuantities\Api\RealProductPuqConfigRepositoryInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\Data as DataHelper;
use Aitoc\ProductUnitsAndQuantities\Helper\ResultConfigBuilder;
use Aitoc\ProductUnitsAndQuantities\Model\RealProductPuqConfig;
use Aitoc\ProductUnitsAndQuantities\Model\RealProductPuqConfigFactory;
use Aitoc\ProductUnitsAndQuantities\Model\ResultAdminProductPuqConfigSaver;
use Exception;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Controller\Adminhtml\Product\Action\Attribute\Save;
use Magento\Catalog\Helper\Product\Edit\Action\Attribute as AttributeHelper;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Store\Model\Store;
use Zend_Validate_Exception;

class SavePlugin
{
    /**
     * @var AttributeHelper
     */
    private $attributeHelper;

    /**
     * @var RealProductPuqConfigRepositoryInterface
     */
    private $realProductPuqConfigRepository;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /**
     * @var DataHelper
     */
    private $dataHelper;

    /**
     * @var ResultAdminProductPuqConfigSaver
     */
    private $resultAdminProductPuqConfigSaver;

    /**
     * @var RealProductPuqConfigFactory
     */
    private $realAdminProductPuqConfigFactory;

    /**
     * @var PuqConfigurationInterface
     */
    private $puqConfiguration;

    /**
     * @var ResultConfigBuilder
     */
    private $resultConfigBuilder;

    /**
     * SavePlugin constructor.
     * @param AttributeHelper $attributeHelper
     * @param RealProductPuqConfigRepositoryInterface $realProductPuqConfigRepository
     * @param ManagerInterface $messageManager
     * @param AuthorizationInterface $authorization
     * @param ProductRepositoryInterface $productRepository
     * @param DataHelper $dataHelper
     * @param ResultAdminProductPuqConfigSaver $resultAdminProductPuqConfigSaver
     * @param RealProductPuqConfigFactory $realAdminProductPuqConfigFactory
     * @param PuqConfigurationInterface $puqConfiguration
     * @param ResultConfigBuilder $resultConfigBuilder
     */
    public function __construct(
        AttributeHelper $attributeHelper,
        RealProductPuqConfigRepositoryInterface $realProductPuqConfigRepository,
        ManagerInterface $messageManager,
        AuthorizationInterface $authorization,
        ProductRepositoryInterface $productRepository,
        DataHelper $dataHelper,
        ResultAdminProductPuqConfigSaver $resultAdminProductPuqConfigSaver,
        RealProductPuqConfigFactory $realAdminProductPuqConfigFactory,
        PuqConfigurationInterface $puqConfiguration,
        ResultConfigBuilder $resultConfigBuilder
    ) {
        $this->attributeHelper = $attributeHelper;
        $this->realProductPuqConfigRepository = $realProductPuqConfigRepository;
        $this->messageManager = $messageManager;
        $this->authorization = $authorization;
        $this->productRepository = $productRepository;
        $this->dataHelper = $dataHelper;
        $this->resultAdminProductPuqConfigSaver = $resultAdminProductPuqConfigSaver;
        $this->realAdminProductPuqConfigFactory = $realAdminProductPuqConfigFactory;
        $this->puqConfiguration = $puqConfiguration;
        $this->resultConfigBuilder = $resultConfigBuilder;
    }

    /**
     * Around execute plugin
     *
     * @param Save $subject
     * @param callable $proceed
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function aroundExecute(Save $subject, callable $proceed)
    {
        try {
            if (!$this->isAllowedForCurrentUser()) {
                return $proceed();
            }

            $request = $subject->getRequest();
            $puqData = $request->getParam('product_units_and_quantities', []);

            $storeId = $this->attributeHelper->getSelectedStoreId();
            $productIds = $this->attributeHelper->getProductIds();

            if (!empty($puqData)) {
                $this->updatePuqInProductsIfNecessary($productIds, $storeId, $puqData);
            }

            return $proceed();
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $proceed();
        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage(
                $e,
                __('Something went wrong while updating the product(s) "Product Units And Quantities" parameters.')
            );

            return $proceed();
        }
    }

    /**
     * @return bool
     */
    private function isAllowedForCurrentUser()
    {
        return $this->authorization->isAllowed(AclInterface::DEFAULT_VALUE);
    }

    /**
     * Update inventory in products
     *
     * @param array $productIds
     * @param int $storeId
     * @param array $puqData
     *
     * @return void
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     * @throws ValidatorException
     * @throws Zend_Validate_Exception
     */
    private function updatePuqInProductsIfNecessary($productIds, $storeId, $puqData): void
    {
        foreach ($productIds as $productId) {
            $this->updatePuqInProductIfNecessary($productId, $storeId, $puqData);
        }
    }

    /**
     * @param $productId
     * @param int $storeId
     * @param $puqData
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     * @throws ValidatorException
     * @throws Zend_Validate_Exception
     */
    private function updatePuqInProductIfNecessary($productId, $storeId, $puqData)
    {
        $product = $this->getProductById($productId);

        if (!$this->isApplicableForProduct($product)) {
            return;
        }

        /*
         * If no real entity and have all use_config - not save
        */
        $productPuqConfig = $this->productPuqDataToResultAdminPuqConfig($productId, $storeId, $puqData);
        $this->saveResultPuqConfig($productPuqConfig);
    }

    /**
     * @param $productId
     * @return ProductInterface
     * @throws NoSuchEntityException
     */
    private function getProductById($productId)
    {
        return $this->productRepository->getById($productId);
    }

    /**
     * @param ProductInterface $product
     * @return bool
     */
    public function isApplicableForProduct(ProductInterface $product)
    {
        return $this->dataHelper->isApplicableForProduct($product);
    }

    /**
     * @param int $productId
     * @param int $storeId
     * @param array $puqData
     * @return ProductPuqConfigInterface
     */
    private function productPuqDataToResultAdminPuqConfig($productId, $storeId, $puqData)
    {
        $sourceAdminPuqConfig = $this->getOrCreatePuqConfigByProductId($productId, $storeId);
        $inputPuqConfig = $this->productPuqDataToAdminPuqConfig($puqData, $productId, $storeId);
        $systemPuqConfig = $this->getSystemPuqConfig();

        return $this->getResultPuqConfig($sourceAdminPuqConfig, $inputPuqConfig, $systemPuqConfig);
    }

    /**
     * @param int $productId
     * @param int $storeId
     * @return RealProductPuqConfig|null
     */
    private function getOrCreatePuqConfigByProductId($productId, $storeId)
    {
        $productPuqConfig = $this->getRealProductPuqConfigByProductId($productId, $storeId);

        if ($storeId && !$productPuqConfig) {
            $productPuqConfig = $this->getRealProductPuqConfigByProductId($productId, Store::DEFAULT_STORE_ID);

            if ($productPuqConfig) {
                $productPuqConfig->setId(null);
            }
        }

        if (!$productPuqConfig) {
            $productPuqConfig = $this->createRealPuqConfig();
        }

        return $productPuqConfig;
    }

    /**
     * @param int $productId
     * @param int $storeId
     * @return RealProductPuqConfig|mixed|null
     */
    private function getRealProductPuqConfigByProductId($productId, $storeId)
    {
        return $this->realProductPuqConfigRepository->getByProductIdAndStoreId($productId, $storeId);
    }

    private function createRealPuqConfig()
    {
        return $this->realAdminProductPuqConfigFactory->create();
    }

    /**
     * @param array $puqData
     * @param $productId
     * @return RealProductPuqConfig|null
     */
    private function productPuqDataToAdminPuqConfig($puqData, $productId, $storeId)
    {
        $puqDataWithUseConfig = $this->addUseConfigValues($puqData);

        $puqProductConfig = $this->createPuqConfig($puqDataWithUseConfig);

        $puqProductConfig
            ->setProductId($productId)
            ->setStoreId($storeId)
        ;


        return $puqProductConfig;
    }

    private function addUseConfigValues($puqData)
    {
        $result = $puqData;

        foreach ($puqData as $key => $value) {
            if ($this->startsWith($key, 'use_config')) {
                continue;
            }

            $result['use_config_' . $key] = false;
        }

        return $result;
    }

    private function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }
    /**
     * @param array $data
     * @return RealProductPuqConfig
     */
    private function createPuqConfig($data = [])
    {
        return $this->realAdminProductPuqConfigFactory->create(['data' => $data]);
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
