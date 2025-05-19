<?php
/**
 * @category   Webkul
 * @package    Webkul_MpSellerBadge
 * @author     Webkul Software Private Limited
 * @copyright  Copyright (c)  Webkul Software Private Limited (https://webkul.com)
 * @license    https://store.webkul.com/license.html
 */
namespace Webkul\MpSellerBadge\Controller\Adminhtml\Badges;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultFactory;

class Createbadge extends Action
{
     /**
      * @var \Magento\Framework\View\Result\PageFactory
      */
    protected $resultPageFactory;

    /**
     * object of ResultFactory
     * @var Resultfactory
     */
    protected $resultFactory;
    
    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
    
        $this->resultFactory = $context->getResultFactory();
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Create badge
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
       /** @var \Magento\Backend\Model\View\Result\Forward $resultForward */
            $resultForward = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
            $resultForward->forward('edit');
            return $resultForward;
    }

    /**
     * Check for is allowed
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_MpSellerBadge::m_badge');
    }
}
