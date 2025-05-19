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

use Aitoc\ProductUnitsAndQuantities\Api\BaseResultProductPuqConfigFactoryInterface;
use Aitoc\ProductUnitsAndQuantities\Api\BaseResultPuqConfigRepositoryInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\BaseResultProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\RealProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\ResultAdminProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfig\PuqConfigWithoutUseConfigSettersInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfigWithoutUseConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfigWithUseConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\SystemPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\PuqConfigurationInterface;
use Aitoc\ProductUnitsAndQuantities\Api\RealProductPuqConfigRepositoryInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\PuqConfig\Adjuster\ByProductTypeId as ByProductTypeIdPuqConfigQtyAdjuster;
use Aitoc\ProductUnitsAndQuantities\Api\Helper\PuqConfig\Mapper\BasePuqConfigMapperInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\PuqConfig\Mapper\ByUseConfigMapper;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\Store;

/**
 * Class BaseResultProductPuqConfigRepository
 */
abstract class BaseResultProductPuqConfigRepository implements BaseResultPuqConfigRepositoryInterface
{
    /**
     * @var RealProductPuqConfigRepositoryInterface
     */
    protected $realProductPuqConfigRepository;

    /**
     * @var PuqConfigurationInterface
     */
    protected $systemPuqConfigHelper;

    /**
     * @var BaseResultProductPuqConfigFactoryInterface
     */
    protected $resultInterfaceFactory;

    /**
     * @var BasePuqConfigMapperInterface
     */
    protected $puqConfigMapper;

    /**
     * @var ByUseConfigMapper
     */
    protected $byUseConfigMapper;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var ByProductTypeIdPuqConfigQtyAdjuster
     */
    protected $byProductTypeIdPuqConfigQtyAdjuster;

    /**
     * ResultProductPuqConfigRepository constructor.
     * @param RealProductPuqConfigRepositoryInterface $adminProductPuqConfigRepository
     * @param PuqConfigurationInterface $systemPuqConfigHelper
     * @param BaseResultProductPuqConfigFactoryInterface $resultProductPuqConfigFactory
     * @param ByUseConfigMapper $byUseConfigMapper
     * @param BasePuqConfigMapperInterface $puqConfigMapper
     * @param ProductRepositoryInterface $productRepository
     * @param ByProductTypeIdPuqConfigQtyAdjuster $byProductTypeIdPuqConfigQtyAdjuster
     */
    public function __construct(
        RealProductPuqConfigRepositoryInterface $adminProductPuqConfigRepository,
        PuqConfigurationInterface $systemPuqConfigHelper,
        BaseResultProductPuqConfigFactoryInterface $resultProductPuqConfigFactory,
        ByUseConfigMapper $byUseConfigMapper,
        BasePuqConfigMapperInterface $puqConfigMapper,
        ProductRepositoryInterface $productRepository,
        ByProductTypeIdPuqConfigQtyAdjuster $byProductTypeIdPuqConfigQtyAdjuster
    ) {
        $this->realProductPuqConfigRepository = $adminProductPuqConfigRepository;
        $this->systemPuqConfigHelper = $systemPuqConfigHelper;
        $this->resultInterfaceFactory = $resultProductPuqConfigFactory;
        $this->byUseConfigMapper = $byUseConfigMapper;
        $this->puqConfigMapper = $puqConfigMapper;
        $this->productRepository = $productRepository;
        $this->byProductTypeIdPuqConfigQtyAdjuster = $byProductTypeIdPuqConfigQtyAdjuster;
    }

    /**
     * @inheritdoc
     * @throws NoSuchEntityException
     */
    public function getByProductIdAndStoreId($productId, $storeId)
    {
        $mergedPuqConfig = $this->getByProductIdAndStoreIdWithoutProductTypeAdjust($productId, $storeId);

        return $this->adjustPuqConfigByProductId($productId, $mergedPuqConfig);
    }

    /**
     * @param int $productId
     * @param int $storeId
     * @return BaseResultProductPuqConfigInterface|mixed
     */
    private function getByProductIdAndStoreIdWithoutProductTypeAdjust($productId, $storeId)
    {
        $systemPuqConfig = $this->systemPuqConfigHelper->getRealPuqSystemConfig();
        $realProductPuqConfig = $this->getRealProductPuqConfig($productId, $storeId);

        if ($storeId && !$realProductPuqConfig) {
            $realProductPuqConfig = $this->getRealProductPuqConfig($productId, Store::DEFAULT_STORE_ID);

            if ($realProductPuqConfig) {
                $realProductPuqConfig->setId(null);
            }
        }

        return $this->mergeSystemAndRealProductPuqConfigs($systemPuqConfig, $realProductPuqConfig);
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
     * @param SystemPuqConfigInterface $systemPuqConfig
     * @param RealProductPuqConfigInterface|null $realProductPuqConfig
     * @return BaseResultProductPuqConfigInterface
     */
    private function mergeSystemAndRealProductPuqConfigs(
        SystemPuqConfigInterface $systemPuqConfig,
        RealProductPuqConfigInterface $realProductPuqConfig = null
    ) {
        return $realProductPuqConfig
            ? $this->getResultPuqConfigByRealPuqConfig($realProductPuqConfig, $systemPuqConfig)
            : $this->getSystemProductPuqConfigValues($systemPuqConfig);
    }

    /**
     * @param PuqConfigWithUseConfigInterface $realProductPuqConfig
     * @param SystemPuqConfigInterface $systemPuqConfig
     * @return mixed
     */
    abstract protected function getResultPuqConfigByRealPuqConfig(
        PuqConfigWithUseConfigInterface $realProductPuqConfig,
        SystemPuqConfigInterface $systemPuqConfig
    );

    /**
     * @param SystemPuqConfigInterface $systemPuqConfig
     * @return ResultAdminProductPuqConfigInterface
     */
    protected function getSystemProductPuqConfigValues(SystemPuqConfigInterface $systemPuqConfig)
    {
        $resultProductPuqConfig = $this->createReturnInterfaceNewInstance();
        $this->copyValuesFromSystemConfig($resultProductPuqConfig, $systemPuqConfig);

        return $resultProductPuqConfig;
    }

    /**
     * @return BaseResultProductPuqConfigInterface|ResultAdminProductPuqConfigInterface
     */
    protected function createReturnInterfaceNewInstance()
    {
        return $this->resultInterfaceFactory->create();
    }

    /**
     * @param BaseResultProductPuqConfigInterface $resultAdminProductPuqConfig
     * @param SystemPuqConfigInterface $systemPuqConfig
     */
    protected function copyValuesFromSystemConfig(
        BaseResultProductPuqConfigInterface $resultAdminProductPuqConfig,
        SystemPuqConfigInterface $systemPuqConfig
    ) {
        $this->puqConfigMapper->mapNotNullValues($systemPuqConfig, $resultAdminProductPuqConfig);
    }

    /**
     * @param int $productId
     * @param BaseResultProductPuqConfigInterface $puqConfig
     * @return PuqConfigWithoutUseConfigInterface
     * @throws NoSuchEntityException
     */
    private function adjustPuqConfigByProductId($productId, BaseResultProductPuqConfigInterface $puqConfig)
    {
        $productTypeId = $this->getProductTypeIdByProductId($productId);

        return $this->adjustPuqConfigByProductTypeId($productTypeId, $puqConfig);
    }

    /**
     * @param int $productId
     * @return string|null
     * @throws NoSuchEntityException
     */
    private function getProductTypeIdByProductId($productId)
    {
        $product = $this->getProductById($productId);

        return $product->getTypeId();
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
     * @param string $productTypeId
     * @param BaseResultProductPuqConfigInterface $puqConfig
     * @return PuqConfigWithoutUseConfigInterface
     */
    private function adjustPuqConfigByProductTypeId($productTypeId, BaseResultProductPuqConfigInterface $puqConfig)
    {
        return $this->byProductTypeIdPuqConfigQtyAdjuster->adjustPuqConfig($productTypeId, $puqConfig);
    }

    /**
     * @inheritdoc
     */
    protected function copyNotUseConfigProductPuqConfigValues(
        $resultProductPuqConfig,
        $realAdminProductPuqConfig
    ) {
        if (!$realAdminProductPuqConfig->getUseConfigReplaceQty()) {
            $resultProductPuqConfig->setReplaceQty($realAdminProductPuqConfig->getReplaceQty());
        }

        if (!$realAdminProductPuqConfig->getUseConfigQtyType()) {
            $resultProductPuqConfig->setQtyType($realAdminProductPuqConfig->getQtyType());
        }

        if (!$realAdminProductPuqConfig->getUseConfigIsQtyDecimal()) {
            $resultProductPuqConfig->setIsQtyDecimal($realAdminProductPuqConfig->getIsQtyDecimal());
        }

        if (!$realAdminProductPuqConfig->getUseConfigUseQuantities()) {
            $resultProductPuqConfig->setUseQuantities($realAdminProductPuqConfig->getUseQuantities());
        }

        if (!$realAdminProductPuqConfig->getUseConfigStartQty()) {
            $resultProductPuqConfig->setStartQty($realAdminProductPuqConfig->getStartQty());
        }

        if (!$realAdminProductPuqConfig->getUseConfigQtyIncrement()) {
            $resultProductPuqConfig->setQtyIncrement($realAdminProductPuqConfig->getQtyIncrement());
        }

        if (!$realAdminProductPuqConfig->getUseConfigEndQty()) {
            $resultProductPuqConfig->setEndQty($realAdminProductPuqConfig->getEndQty());
        }

        if (!$realAdminProductPuqConfig->getUseConfigAllowUnits()) {
            $resultProductPuqConfig->setAllowUnits($realAdminProductPuqConfig->getAllowUnits());
        }

        if (!$realAdminProductPuqConfig->getUseConfigPricePer()) {
            $resultProductPuqConfig->setPricePer($realAdminProductPuqConfig->getPricePer());
        }

        if (!$realAdminProductPuqConfig->getUseConfigPricePerDivider()) {
            $resultProductPuqConfig->setPricePerDivider($realAdminProductPuqConfig->getPricePerDivider());
        }
    }

    /**
     * @param PuqConfigWithUseConfigInterface $forGetUseConfigPuqConfig
     * @param PuqConfigWithoutUseConfigSettersInterface $toCopyPuqConfig
     * @param SystemPuqConfigInterface $systemPuqConfig
     * @return void
     */
    protected function copyUseConfigProductPuqConfigValues(
        PuqConfigWithUseConfigInterface $forGetUseConfigPuqConfig,
        PuqConfigWithoutUseConfigSettersInterface $toCopyPuqConfig,
        SystemPuqConfigInterface $systemPuqConfig
    ) {
        $this
            ->byUseConfigMapper
            ->applyByUseConfigValues($forGetUseConfigPuqConfig, $toCopyPuqConfig, $systemPuqConfig);
    }
}
