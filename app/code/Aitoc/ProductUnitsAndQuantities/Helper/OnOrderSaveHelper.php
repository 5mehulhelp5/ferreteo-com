<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Helper;

use Aitoc\ProductUnitsAndQuantities\Api\OrderItemPuqConfigRepositoryInterface;
use Aitoc\ProductUnitsAndQuantities\Model\OrderItemPuqConfigFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;

/**
 * Class OnOrderSaveHelper
 */
class OnOrderSaveHelper
{
    /**
     * @var OrderItemPuqConfigFactory
     */
    private $orderItemPuqConfigFactory;

    /**
     * @var OrderItemPuqConfigRepositoryInterface
     */
    private $orderItemRepository;

    /**
     * @var Data
     */
    private $helper;

    /**
     * OnOrderSaveHelper constructor.
     * @param OrderItemPuqConfigFactory $orderItemPuqFactory
     * @param OrderItemPuqConfigRepositoryInterface $orderItemPuqConfigRepository
     * @param Data $helper
     */
    public function __construct(
        OrderItemPuqConfigFactory $orderItemPuqFactory,
        OrderItemPuqConfigRepositoryInterface $orderItemPuqConfigRepository,
        Data $helper
    ) {
        $this->orderItemPuqConfigFactory = $orderItemPuqFactory;
        $this->orderItemRepository = $orderItemPuqConfigRepository;
        $this->helper = $helper;
    }

    /**
     * @param OrderInterface $order
     * @throws LocalizedException
     */
    public function saveOrderItemPuqConfigForOrder(OrderInterface $order)
    {
        //todo: fix interface incompatible methods calls
        $orderItems = $order->getAllItems();
        $storeId = $order->getStoreId();

        //todo: add Possible interfaces to phpdoc
        /** @var OrderItemInterface $orderItem */
        foreach ($orderItems as $orderItem) {
            $orderItemId = $orderItem->getId();
            $productId = $orderItem->getProductId();
            $puqProductConfig = $this->helper->getResultProductPuqConfigByProductIdAndStockId($productId, $storeId);
            $orderItem = $this->orderItemPuqConfigFactory->create();
            $orderItem->setOrderItemId($orderItemId);
            $orderItem->setPricePer($puqProductConfig->getPricePer());
            $orderItem->setPricePerDivider($puqProductConfig->getPricePerDivider());

            $this->orderItemRepository->save($orderItem);
        }
    }
}
