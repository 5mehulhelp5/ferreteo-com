<?php
namespace Magecomp\Cityandregionmanager\Controller\Adminhtml\Cities;

use Magento\Backend\App\Action;
use Magecomp\Cityandregionmanager\Model\Cities;

class Delete extends Action
{
    protected $_model;

    public function __construct(Action\Context $context, Cities $model)
    {
        parent::__construct($context);
        $this->_model = $model;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->_model;
        $model->load($id);

        if ($model->getId()) {
            $model->delete();
            $this->messageManager->addSuccessMessage(__('Record is deleted successfully!'));
            $this->_redirect('*/*/index');
        } else {
            $this->messageManager->addErrorMessage(__('Record  not found.'));
            $this->_redirect('*/*/index');
        }

    }
}