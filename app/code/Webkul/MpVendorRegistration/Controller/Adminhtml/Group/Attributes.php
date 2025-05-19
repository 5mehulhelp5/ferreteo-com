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

class Attributes extends \Magento\Backend\App\Action
{
    protected $resultLayoutFactory;
    /**
     * Undocumented function
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
    ) {
        $this->resultLayoutFactory = $resultLayoutFactory;
        $this->_registry = $registry;
        parent::__construct($context);
    }
    /**
     * allow to user
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
     * @return page
     */
    public function execute()
    {
        $this->_registry->register('group_id', $this->getRequest()->getParam('id'));
        $resultLayout = $this->resultLayoutFactory->create();
        $resultLayout->getLayout()
                    ->getBlock('group.edit.tab.attributes');
        return $resultLayout;
    }
}
