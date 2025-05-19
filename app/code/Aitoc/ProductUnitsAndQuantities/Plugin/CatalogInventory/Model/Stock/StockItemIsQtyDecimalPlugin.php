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
use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class StockItemMinQtyPlugin
 */
class StockItemIsQtyDecimalPlugin extends BaseStockItemPlugin
{
    /**
     * @param StockItemInterface $stock
     * @param float $value
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function afterGetIsQtyDecimal(StockItemInterface $stock, $value)
    {
        $productId = $stock->getProductId();
        $storeId = $this->getCurrentStoreId();
        $resultProductPuqConfig = $this->helper->getResultProductPuqConfigByProductIdAndStockId($productId, $storeId);

        if (!$this->isOverridableQtyParamsControl($resultProductPuqConfig)) {
            return $value;
        }

        return $this->getIsQtyDecimal($resultProductPuqConfig);
    }

    /**
     * @param ResultFrontendProductPuqConfigInterface $resultFrontendProductProductPuqConfig
     * @return float|null
     */
    private function getIsQtyDecimal(
        ResultFrontendProductPuqConfigInterface $resultFrontendProductProductPuqConfig
    ) {
        return $resultFrontendProductProductPuqConfig->getIsQtyDecimal();
    }
}
