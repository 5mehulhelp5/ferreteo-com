<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Observer;

use Aitoc\ProductUnitsAndQuantities\Helper\OnOrderSaveHelper;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\OrderRepositoryInterface;

/**
 * Class SaveOrderObserver
 */
class CheckoutOnepageControllerSuccessActionObserver implements ObserverInterface
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var OnOrderSaveHelper
     */
    private $onOrderSaveHelper;

    /**
     * SaveOrderObserver constructor.
     * @param OrderRepositoryInterface $orderRepository
     * @param OnOrderSaveHelper $onOrderSaveHelper
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        OnOrderSaveHelper $onOrderSaveHelper
    ) {
        $this->onOrderSaveHelper = $onOrderSaveHelper;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @inheritdoc
     * @throws LocalizedException
     */
    public function execute(Observer $observer)
    {
        $orderIds = $observer->getData('order_ids');

        foreach ($orderIds as $orderId) {
            $order = $this->orderRepository->get($orderId);

            $this->onOrderSaveHelper->saveOrderItemPuqConfigForOrder($order);
        }

        return $this;
    }
}
