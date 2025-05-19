<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Block\Quantities;

use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class CartQuantities
 */
class CartQuantities extends Render
{
    const KEY_PRODUCT_ID = 'productId';
    const KEY_ITEM_ID = 'itemId';

    protected $_template = 'Aitoc_ProductUnitsAndQuantities::renderer/cart.phtml';

    /**
     * @return int
     */
    public function getProductId()
    {
        return $this->getData(self::KEY_PRODUCT_ID);
    }

    /**
     * @param int $productId
     * @return self
     */
    public function setProductId($productId)
    {
        $this->setData(self::KEY_PRODUCT_ID, $productId);

        return $this;
    }

    /**
     * @return int
     */
    public function getItemId()
    {
        return $this->getData(self::KEY_ITEM_ID);
    }

    /**
     * @param int $itemId
     * @return self
     */
    public function setItemId($itemId)
    {
        $this->setData(self::KEY_ITEM_ID, $itemId);

        return $this;
    }

    /**
     * @throws NoSuchEntityException
     */
    public function getStoreId()
    {
        $this->_storeManager->getStore()->getId();
    }
}
