<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Plugin\CatalogInventory\Model\Stock;

use Aitoc\ProductUnitsAndQuantities\Api\Data\ResultFrontendProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfig\PuqConfigWithoutUseConfigGettersInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\ReplaceQtyInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\Data as UnitsAndQuantitiesHelper;
use Aitoc\ProductUnitsAndQuantities\Helper\UseQuantitiesStringToArrayConvector;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class ItemMinMaxIncrementQtyPlugin
 */
class BaseStockItemPlugin
{
    /** @var UnitsAndQuantitiesHelper */
    protected $helper;

    /**
     * @var UseQuantitiesStringToArrayConvector
     */
    protected $useQuantitiesStringToArrayConvector;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * ItemMinMaxIncrementQtyPlugin constructor.
     * @param UnitsAndQuantitiesHelper $helper
     * @param UseQuantitiesStringToArrayConvector $useQuantitiesStringToArrayConvector
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        UnitsAndQuantitiesHelper $helper,
        UseQuantitiesStringToArrayConvector $useQuantitiesStringToArrayConvector,
        StoreManagerInterface $storeManager
    ) {
        $this->helper = $helper;
        $this->useQuantitiesStringToArrayConvector = $useQuantitiesStringToArrayConvector;
        $this->storeManager = $storeManager;
    }

    /**
     * @return int
     * @throws NoSuchEntityException
     */
    protected function getCurrentStoreId()
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
     * @param ResultFrontendProductPuqConfigInterface $resultProductPuqConfig
     * @return bool
     */
    protected function isOverridableQtyParamsControl(
        ResultFrontendProductPuqConfigInterface $resultProductPuqConfig
    ) {
        return $resultProductPuqConfig->getReplaceQty() != ReplaceQtyInterface::OFF;
    }

    /**
     * @param PuqConfigWithoutUseConfigGettersInterface $productPuqConfig
     * @return float[]
     */
    protected function getUseQuantitiesAsArray(PuqConfigWithoutUseConfigGettersInterface $productPuqConfig)
    {
        $quantitiesStr = $productPuqConfig->getUseQuantities();

        return $this->useQuantitiesStringToArrayConvector->convert($quantitiesStr);
    }
}
