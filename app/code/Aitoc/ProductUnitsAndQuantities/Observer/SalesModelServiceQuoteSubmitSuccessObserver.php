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

/**
 * Class SalesModelServiceQuoteSubmitSuccessObserver
 */
class SalesModelServiceQuoteSubmitSuccessObserver implements ObserverInterface
{
    /**
     * @var OnOrderSaveHelper
     */
    private $onOrderSaveHelper;

    /**
     * SalesModelServiceQuoteSubmitSuccessObserver constructor.
     * @param OnOrderSaveHelper $onOrderSaveHelper
     */
    public function __construct(OnOrderSaveHelper $onOrderSaveHelper)
    {
        $this->onOrderSaveHelper = $onOrderSaveHelper;
    }

    /**
     * @inheritdoc
     * @throws LocalizedException
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getOrder();
        $this->onOrderSaveHelper->saveOrderItemPuqConfigForOrder($order);
    }
}
