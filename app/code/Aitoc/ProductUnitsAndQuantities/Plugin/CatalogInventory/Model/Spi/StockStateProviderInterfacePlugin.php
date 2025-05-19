<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Plugin\CatalogInventory\Model\Spi;

use Aitoc\ProductUnitsAndQuantities\Api\Data\ResultFrontendProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\QtyTypeInterface;
use Aitoc\ProductUnitsAndQuantities\Api\ResultFrontendProductPuqConfigRepositoryInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\UseQuantitiesHelper;
use Aitoc\ProductUnitsAndQuantities\Helper\UseQuantitiesStringToArrayConvector;
use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\CatalogInventory\Model\Spi\StockStateProviderInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class StockStateProviderInterfacePlugin
 */
class StockStateProviderInterfacePlugin
{
    const VALIDATION_MESSAGE = 'Quantity "%1" is not in allowed values.';

    /**
     * @var float
     */
    private $itemQty;

    /**
     * @var StockItemInterface
     */
    private $stockItem;

    /**
     * @var ResultFrontendProductPuqConfigRepositoryInterface
     */
    private $resultFrontendProductPuqConfigRepository;

    /**
     * @var UseQuantitiesStringToArrayConvector
     */
    private $useQuantitiesStringToArrayConvector;

    /**
     * @var UseQuantitiesHelper
     */
    private $useQuantitiesHelper;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * StockStateProviderInterfacePlugin constructor.
     * @param ResultFrontendProductPuqConfigRepositoryInterface $resultFrontendProductPuqConfigRepository
     * @param UseQuantitiesStringToArrayConvector $useQuantitiesStringToArrayConvector
     * @param UseQuantitiesHelper $useQuantitiesHelper
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResultFrontendProductPuqConfigRepositoryInterface $resultFrontendProductPuqConfigRepository,
        UseQuantitiesStringToArrayConvector $useQuantitiesStringToArrayConvector,
        UseQuantitiesHelper $useQuantitiesHelper,
        StoreManagerInterface $storeManager
    ) {
        $this->resultFrontendProductPuqConfigRepository = $resultFrontendProductPuqConfigRepository;
        $this->useQuantitiesStringToArrayConvector = $useQuantitiesStringToArrayConvector;
        $this->useQuantitiesHelper = $useQuantitiesHelper;
        $this->storeManager = $storeManager;
    }

    /**
     * @param StockStateProviderInterface $stockStateProvider
     * @param StockItemInterface $stockItem
     * @param int|float $itemQty
     * @param int|float $qtyToCheck
     * @param int $origQty
     */
    public function beforeCheckQuoteItemQty(
        StockStateProviderInterface $stockStateProvider,
        StockItemInterface $stockItem,
        $itemQty,
        $qtyToCheck,
        $origQty = 0
    ) {
        $this->itemQty = $itemQty;
        $this->stockItem = $stockItem;
    }

    /**
     * @param StockStateProviderInterface $stockStateProvider
     * @param DataObject $result
     * @return DataObject
     * @throws NoSuchEntityException
     */
    public function afterCheckQuoteItemQty(StockStateProviderInterface $stockStateProvider, DataObject $result)
    {
        if ($result->getHasError()) {
            return $result;
        }

        $storeId = $this->getCurrentStoreId();

        return $this->checkQuoteItemQtyByUseQuantitiesIfRequired($result, $this->stockItem, $storeId, $this->itemQty);
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
     * @param DataObject $result
     * @param StockItemInterface $stockItem
     * @param int $storeId
     * @param float $itemQty
     * @return DataObject
     */
    private function checkQuoteItemQtyByUseQuantitiesIfRequired(
        DataObject $result,
        StockItemInterface $stockItem,
        $storeId,
        $itemQty
    ) {
        if (!$this->isValidationRequired($stockItem, $storeId)) {
            return $result;
        }

        if ($this->isValidQtyByUseQuantities($stockItem, $storeId, $itemQty)) {
            return $result;
        }

        return $this->formatError($result, $stockItem, $storeId, $itemQty);
    }

    /**
     * @param StockItemInterface $stockItem
     * @param int $storeId
     * @return bool
     */
    private function isValidationRequired(StockItemInterface $stockItem, $storeId)
    {
        $resultFrontendProductPuqConfig = $this->getResultProductPuqConfigByStockItem($stockItem, $storeId);

        return  $resultFrontendProductPuqConfig->getQtyType() == QtyTypeInterface::TYPE_STATIC;
    }

    /**
     * @param StockItemInterface $stockItem
     * @param int $storeId
     * @return ResultFrontendProductPuqConfigInterface
     */
    private function getResultProductPuqConfigByStockItem(StockItemInterface $stockItem, $storeId)
    {
        $productId = $stockItem->getProductId();

        return $this->resultFrontendProductPuqConfigRepository->getByProductIdAndStoreId($productId, $storeId);
    }

    /**
     * @param StockItemInterface $stockItem
     * @param int $storeId
     * @param float $qty
     * @return bool
     */
    private function isValidQtyByUseQuantities(StockItemInterface $stockItem, $storeId, $qty)
    {
        $possibleValues = $this->getPossibleValuesByStockItem($stockItem, $storeId);

        return in_array($qty, $possibleValues);
    }

    /**
     * @param StockItemInterface $stockItem
     * @param int $storeId
     * @return float[]
     */
    private function getPossibleValuesByStockItem(StockItemInterface $stockItem, $storeId)
    {
        $resultFrontendProductPuqConfig = $this->getResultProductPuqConfigByStockItem($stockItem, $storeId);
        $useQuantitiesStr = $resultFrontendProductPuqConfig->getUseQuantities();

        return $this->useQuantitiesStringToArrayConvector->convert($useQuantitiesStr);
    }

    /**
     * @param DataObject $result
     * @param StockItemInterface $stockItem
     * @param int $storeId
     * @param int|float $itemQty
     * @return DataObject
     */
    private function formatError(DataObject $result, StockItemInterface $stockItem, $storeId, $itemQty)
    {
        $validationMessage = $this->getValidationMessage($stockItem, $storeId, $itemQty);

        $result->setHasError(true)
            ->setMessage($validationMessage)
            ->setErrorCode('qty_use_quantities')
            ->setQuoteMessage(__('Please correct the quantity for some products.'))
            ->setQuoteMessageIndex('qty');

        return $result;
    }

    /**
     * @param StockItemInterface $stockItem
     * @param int $storeId
     * @param float $itemQty
     * @return string
     */
    private function getValidationMessage(StockItemInterface $stockItem, $storeId, $itemQty)
    {
        $possibleValues = $this->getPossibleValuesByStockItem($stockItem, $storeId);
        $downValue = $this->useQuantitiesHelper->getDownValue($itemQty, $possibleValues);
        $upValue = $this->useQuantitiesHelper->getUpValue($itemQty, $possibleValues);

        $nearestMsg = ($downValue === $upValue)
            ? __(ValidationMessagesInterface::NEAREST_VALUE_SINGLE, $downValue)
            : __(ValidationMessagesInterface::NEAREST_VALUE_MULTIPLE, $downValue, $upValue);

        $msg = __(ValidationMessagesInterface::NOT_ALLOWED_VALUE, $itemQty);

        return $msg . ' ' . $nearestMsg;
    }
}
