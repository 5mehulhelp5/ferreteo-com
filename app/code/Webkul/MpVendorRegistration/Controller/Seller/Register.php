<?php
/**
 * Webkul Software
 *
 * @category  Webkul
 * @package   Webkul_MpVendorRegistration
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\MpVendorRegistration\Controller\Seller;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Webkul MpVendorRegistration Account Create Controller.
 */
class Register extends Action
{
    /**
     * @var \Magento\Framework\Controller\Result\ForwardFactory
     */
    protected $resultForwardFactory;
    /**
     * Undocumented function
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
     * @param \Webkul\MpVendorRegistration\Helper\Data $helper
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        \Webkul\MpVendorRegistration\Helper\Data $helper
    ) {
        $this->customerSession = $customerSession;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->helper = $helper;
        parent::__construct($context);
    }

    public function execute()
    {
        if ($this->helper->isUserLoggedIn()) {
            return $this->resultRedirectFactory
                        ->create()
                        ->setPath('marketplace/account/dashboard');
        } elseif (!$this->helper->getConfigData('visible_registration')) {
            return $this->resultRedirectFactory
                        ->create()
                        ->setPath('customer/account/create');
        } else {
            $label = 'Vendor Registration';
            $resultPage = $this->resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->set(__($label));
            return $resultPage;
        }
    }
}
