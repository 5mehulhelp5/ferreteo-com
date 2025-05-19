<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Api\Data;

/**
 * Interface OrderItemPuqConfigInterface
 */
interface OrderItemPuqConfigInterface
{
    const ITEM_ID = 'item_id';
    const ORDER_ITEM_ID = 'order_item_id';
    const PRICE_PER = 'price_per';
    const DIVIDER = 'price_per_divider';

    /**
     * @return int
     */
    public function getItemId();

    /**
     * @param int $itemId
     * @return self
     */
    public function setItemId($itemId);

    /**
     * @return int
     */
    public function getOrderItemId();

    /**
     * @param int $orderItemId
     * @return self
     */
    public function setOrderItemId($orderItemId);

    /**
     * @return string
     */
    public function getPricePer();

    /**
     * @param string $pricePer
     * @return self
     */
    public function setPricePer($pricePer);

    /**
     * @return string
     */
    public function getPricePerDivider();

    /**
     * @param string $pricePerDivider
     * @return self
     */
    public function setPricePerDivider($pricePerDivider);
}
