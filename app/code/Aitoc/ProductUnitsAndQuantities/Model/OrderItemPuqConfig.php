<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Model;

use Aitoc\ProductUnitsAndQuantities\Api\Data\OrderItemPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Model\ResourceModel\OrderItemPuqConfig as OrderItemResource;
use Magento\Framework\Model\AbstractModel;

/**
 * Class OrderItem
 */
class OrderItemPuqConfig extends AbstractModel implements OrderItemPuqConfigInterface
{
    /**
     * Prefix for events
     * @var string
     */
    protected $_eventPrefix = 'aitoc_productunitsandquantities_order';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(OrderItemResource::class);
    }

    /**
     * @inheritdoc
     */
    public function getItemId()
    {
        return $this->getData(OrderItemPuqConfigInterface::ITEM_ID);
    }

    /**
     * @inheritdoc
     */
    public function setItemId($itemId)
    {
        return $this->setData(OrderItemPuqConfigInterface::ITEM_ID, $itemId);
    }

    /**
     * @inheritdoc
     */
    public function getOrderItemId()
    {
        return $this->getData(OrderItemPuqConfigInterface::ORDER_ITEM_ID);
    }

    /**
     * @inheritdoc
     */
    public function setOrderItemId($orderItemId)
    {
        return $this->setData(OrderItemPuqConfigInterface::ORDER_ITEM_ID, $orderItemId);
    }

    /**
     * @inheritdoc
     */
    public function getPricePer()
    {
        return $this->getData(OrderItemPuqConfigInterface::PRICE_PER);
    }

    /**
     * @inheritdoc
     */
    public function setPricePer($pricePer)
    {
        return $this->setData(OrderItemPuqConfigInterface::PRICE_PER, $pricePer);
    }

    /**
     * @inheritdoc
     */
    public function getPricePerDivider()
    {
        return $this->getData(OrderItemPuqConfigInterface::DIVIDER);
    }

    /**
     * @inheritdoc
     */
    public function setPricePerDivider($pricePerDivider)
    {
        return $this->setData(OrderItemPuqConfigInterface::DIVIDER, $pricePerDivider);
    }
}
