<?php

namespace Magecomp\Cancelorder\Controller\Adminhtml\Cancelorder;

use Magecomp\Cancelorder\Model\ResourceModel\Cancelorder\CollectionFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;

class Massdelete extends \Magento\Backend\App\Action
{
    protected $filter;
    protected $_collectionFactory;
    protected $resultPageFactory;

    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        ResultFactory $ResultFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $ResultFactory;
        $this->_collectionFactory = $collectionFactory;
        $this->filter = $filter;
    }

    public function execute()
    {
        $collection = $this->filter->getCollection($this->_collectionFactory->create());
        $collectionSize = $collection->getSize();
        foreach ($collection as $item) {
            $item->delete();
        }
        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $collectionSize));
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magecomp_Assignorder::magecomp_assignorder');
    }
}
