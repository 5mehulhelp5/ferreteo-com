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
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\QtyTypeInterface;
use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class StockItemMinQtyPlugin
 */
class StockItemIncrementQtyPlugin extends BaseStockItemPlugin
{
    /**
     * @param StockItemInterface $stock
     * @param float $value
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function afterGetQtyIncrements(StockItemInterface $stock, $value)
    {
        $storeId = $this->getCurrentStoreId();
        $resultProductPuqConfig = $this->helper->getResultProductPuqConfigByProductIdAndStockId($stock->getProductId(), $storeId);

        if (!$this->isOverridableQtyParamsControl($resultProductPuqConfig)) {
            return $value;
        }

        return $this->getQtyIncrement($resultProductPuqConfig);
    }

    /**
     * @param ResultFrontendProductPuqConfigInterface $resultFrontendProductProductPuqConfig
     * @return float
     */
    private function getQtyIncrement(
        ResultFrontendProductPuqConfigInterface $resultFrontendProductProductPuqConfig
    ) {
        return ($resultFrontendProductProductPuqConfig->getQtyType() === QtyTypeInterface::TYPE_DYNAMIC)
            ? $resultFrontendProductProductPuqConfig->getQtyIncrement()
            : 0.00;
    }
}
