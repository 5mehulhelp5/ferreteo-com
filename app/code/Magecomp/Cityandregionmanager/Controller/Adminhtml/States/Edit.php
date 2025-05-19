<?php
namespace Magecomp\Cityandregionmanager\Controller\Adminhtml\States;

use Magento\Backend\App\Action;

class Edit extends Action
{
    public function __construct(Action\Context $context)
    {
        parent::__construct($context);
    }

    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->getPage()->getConfig()->getTitle()->prepend(isset($this->getRequest()->getParams()["id"]) ? __('Edit State') : __('Add New State'));
        $this->_view->renderLayout();
    }
}