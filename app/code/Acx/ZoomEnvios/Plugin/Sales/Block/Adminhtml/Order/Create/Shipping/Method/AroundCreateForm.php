<?php
/**
 * Ferreteo C.A.
 */
namespace Acx\ZoomEnvios\Plugin\Sales\Block\Adminhtml\Order\Create\Shipping\Method;

use Magento\Sales\Block\Adminhtml\Order\Create\Shipping\Method\Form;
use Acx\ZoomEnvios\Helper\Data;

class AroundCreateForm
{
    /**
     * @var Data
     */
    protected $helper;
    /**
     * AfterCreateForm constructor.
     * @param Data $helper
     */
    public function __construct(
        Data $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * @param Form $subject
     * @param $result
     * @return bool
     */
    public function aroundIsMethodActive(Form $subject, callable $proceed, $code)
    {
        $storeId = $subject->getAddress()->getQuote()->getStoreId();
        $selectStore = $this->helper->getPreSelect($storeId);
        $getActive = $subject->getActiveMethodRate();
        if (!$getActive) {
            if ($selectStore) {
                if ($code == "zoomenvios") {
                    return true;
                }
            }
        }
        return $proceed($code);
    }
}
