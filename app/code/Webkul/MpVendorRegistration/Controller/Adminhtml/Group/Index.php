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
namespace Webkul\MpVendorRegistration\Controller\Adminhtml\Group;

class Index extends \Magento\Backend\App\Action
{
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
     * allowed to user
     *
     * @return authorization
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_MpVendorRegistration::group');
    }
    /**
     * create page
     *
     * @return resultPage
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        
        $resultPage->setActiveMenu('Webkul_Marketplace::menu');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Vendor Registration Group'));
        $resultPage->addBreadcrumb(__('Manage Vendor Registration Group'), __('Manage Vendor Registration Group'));
        
        return $resultPage;
    }
}
