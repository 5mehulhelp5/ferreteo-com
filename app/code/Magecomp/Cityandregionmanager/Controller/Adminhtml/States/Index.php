<?php
namespace Magecomp\Cityandregionmanager\Controller\Adminhtml\States;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $resultPageFactory;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magecomp_Cityandregionmanager::states');
        $resultPage->getConfig()->getTitle()->prepend(__('States'));

        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magecomp_Cityandregionmanager::states');
    }
}