<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Plugin\Wishlist\Model;

use Magento\Catalog\Model\Product;
use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\DataObject;
use Magento\Wishlist\Model\Wishlist;

/**
 * Class WishlistPlugin
 */
class WishlistPlugin
{
    private $stockRegistryInterface;

    /**
     * WishlistPlugin constructor.
     * @param StockRegistryInterface $stockRegistryInterface
     */
    public function __construct(StockRegistryInterface $stockRegistryInterface)
    {
        $this->stockRegistryInterface = $stockRegistryInterface;
    }

    /**
     * @param Wishlist $subject
     * @param int|Product $product
     * @param DataObject|array|string|null $buyRequest
     * @param bool $forciblySetQty
     * @return array|null
     */
    public function beforeAddNewItem(Wishlist $subject, $product, $buyRequest = null, $forciblySetQty = false)
    {
        if (!isset($buyRequest)) {
            return null;
        }

        if ($buyRequest instanceof DataObject) {
            $this->addQtyToDataObjectIfRequired($product, $buyRequest);
        } elseif (is_array($buyRequest)) {
            $this->addQtyToArrayIfRequired($product, $buyRequest);
        } else {
            return null;
        }

        return [$product, $buyRequest, $forciblySetQty];
    }

    /**
     * @param Product $product
     * @param DataObject $buyRequest
     */
    private function addQtyToDataObjectIfRequired(Product $product, DataObject $buyRequest)
    {
        if ($buyRequest->getQty()) {
            return;
        }

        $minSaleQty = $this->getMinSaleQty($product);
        $buyRequest->setQty($minSaleQty);
    }

    /**
     * @param Product $product
     * @param DataObject $buyRequest
     */
    private function addQtyToArrayIfRequired(Product $product, DataObject $buyRequest)
    {
        if ($buyRequest['qty']) {
            return;
        }

        $minQty = $this->getMinSaleQty();
        $buyRequest->setQty($minQty);
    }

    /**
     * @param Product $product
     * @return float
     */
    private function getMinSaleQty(Product $product)
    {
        $stockItem = $this->getStockItem($product);

        return $stockItem->getMinSaleQty();
    }

    /**
     * @param Product $product
     * @return StockItemInterface
     */
    private function getStockItem(Product $product)
    {
        $productId = $product->getId();
        $websiteId = $product->getStore()->getWebsiteId();
        return $this->stockRegistryInterface->getStockItem($productId, $websiteId);
    }
}
