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
class WishlistQuantities extends Render
{
    const KEY_PRODUCT_ID = 'productId';
    const KEY_STORE_ID = 'storeId';

    protected $_template = 'Aitoc_ProductUnitsAndQuantities::renderer/wishlist.phtml';

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
     * @throws NoSuchEntityException
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }
}
