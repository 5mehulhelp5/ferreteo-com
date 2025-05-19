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
class StockItemMinQtyPlugin extends BaseStockItemPlugin
{
    /**
     * @param StockItemInterface $stock
     * @param float $value
     * @return float
     * @throws NoSuchEntityException
     */
    public function afterGetMinSaleQty(StockItemInterface $stock, $value)
    {
        $productId = $stock->getProductId();
        $storeId = $this->getCurrentStoreId();

        $resultProductPuqConfig = $this->helper->getResultProductPuqConfigByProductIdAndStockId($productId, $storeId);

        if (!$this->isOverridableQtyParamsControl($resultProductPuqConfig)) {
            return $value;
        }

        switch ($resultProductPuqConfig->getQtyType()) {
            case QtyTypeInterface::TYPE_STATIC:
                return $this->getMinUseQuantities($resultProductPuqConfig);
            case QtyTypeInterface::TYPE_DYNAMIC:
                return $resultProductPuqConfig->getStartQty();
            default:
                throw new \LogicException('Unknown QtyType: ' . $resultProductPuqConfig->getQtyType());
        }
    }

    /**
     * @param ResultFrontendProductPuqConfigInterface $resultProductPuqConfig
     * @return float
     */
    private function getMinUseQuantities(ResultFrontendProductPuqConfigInterface $resultProductPuqConfig)
    {
        return min($this->getUseQuantitiesAsArray($resultProductPuqConfig));
    }
}
