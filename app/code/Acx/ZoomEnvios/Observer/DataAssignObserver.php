<?php
/**
 * Acx_ZoomEnvios Magento Extension
 */

namespace Acx\ZoomEnvios\Observer;

use Magento\Framework\Event\ObserverInterface;

class DataAssignObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $quote = $observer->getQuote();
        $order = $observer->getOrder();

        if ($quote->getPickupOffice()) {
        	$order->setPickupOffice($quote->getPickupOffice());
        }
        return $this;
    }
}
