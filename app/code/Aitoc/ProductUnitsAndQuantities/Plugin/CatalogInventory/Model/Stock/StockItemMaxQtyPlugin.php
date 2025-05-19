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

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfig\PuqConfigWithoutUseConfigGettersInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\QtyTypeInterface;
use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class StockItemMinQtyPlugin
 */
class StockItemMaxQtyPlugin extends BaseStockItemPlugin
{
    /**
     * @param StockItemInterface $stock
     * @param float $value
     * @return float
     * @throws NoSuchEntityException
     */
    public function afterGetMaxSaleQty(StockItemInterface $stock, $value)
    {
        $productId = $stock->getProductId();
        $storeId = $this->getCurrentStoreId();
        $resultProductPuqConfig = $this->helper->getResultProductPuqConfigByProductIdAndStockId($productId, $storeId);

        if (!$this->isOverridableQtyParamsControl($resultProductPuqConfig)) {
            return $value;
        }

        switch ($resultProductPuqConfig->getQtyType()) {
            case QtyTypeInterface::TYPE_DYNAMIC:
                return $resultProductPuqConfig->getEndQty();
            case QtyTypeInterface::TYPE_STATIC:
                return $this->getMaxUseQuantities($resultProductPuqConfig);
            default:
                throw new \LogicException('Unknown QtyType: ' . $resultProductPuqConfig->getQtyType());
        }
    }

    /**
     * @param PuqConfigWithoutUseConfigGettersInterface $resultProductPuqConfig
     * @return int
     */
    private function getMaxUseQuantities(PuqConfigWithoutUseConfigGettersInterface $resultProductPuqConfig)
    {
        return max($this->getUseQuantitiesAsArray($resultProductPuqConfig));
    }
}
