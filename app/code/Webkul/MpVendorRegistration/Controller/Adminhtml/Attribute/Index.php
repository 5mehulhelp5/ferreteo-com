<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_MpVendorRegistration
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\MpVendorRegistration\Controller\Adminhtml\Attribute;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var resultPageFactory
     */
    protected $resultPageFactory;
    /**
     * Undocumented function
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
    /**
     * allowed to the user
     *
     * @return authorization
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_MpVendorRegistration::attribute');
    }
    /**
     * set result page
     *
     * @return result page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        
        $resultPage->setActiveMenu('Webkul_Marketplace::menu');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Vendor Registration Attribute'));
        $resultPage->addBreadcrumb(
            __('Manage Vendor Registration Attribute'),
            __('Manage Vendor Registration Attribute')
        );
        
        return $resultPage;
    }
}
