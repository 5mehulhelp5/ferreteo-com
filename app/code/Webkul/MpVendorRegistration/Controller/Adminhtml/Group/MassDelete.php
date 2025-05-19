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

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Webkul\MpVendorRegistration\Model\ResourceModel\VendorRegistrationGroup\CollectionFactory;
use Webkul\MpVendorRegistration\Model\VendorRegistrationAssignGroupFactory;

class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $groupCollectionFactory;

    /**
     * @var CollectionFactory
     */
    protected $vendorAssignGroupFactory;

    /**
     * Undocumented function
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Magento\Framework\Registry $registry
     * @param CollectionFactory $collectionFactory
     * @param VendorRegistrationAssignGroupFactory $vendorAssignGroupFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\Framework\Registry $registry,
        CollectionFactory $collectionFactory,
        VendorRegistrationAssignGroupFactory $vendorAssignGroupFactory
    ) {
        $this->filter = $filter;
        $this->groupCollectionFactory = $collectionFactory;
        $this->vendorAssignGroupFactory = $vendorAssignGroupFactory;
        $this->registry = $registry;
        parent::__construct($context);
    }
    /**
     * Check for is allowed
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization
        ->isAllowed(
            'Webkul_MpVendorRegistration::group'
        );
    }

    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        try {
            $collection = $this->filter->getCollection($this->groupCollectionFactory->create());
            $attributeModel = $this->vendorAssignGroupFactory->create();
            $count = 0;
            $errCount = 0;
            foreach ($collection as $model) {
                if ($model->getGroupByAdmin() != 1) {
                    $groupCollection =  $attributeModel->getCollection()->addFieldToFilter('group_id', $model->getId());
                    foreach ($groupCollection as $groupModel) {
                        $groupModel->delete();
                    }
                    $model->delete();
                    $count++;
                } else {
                    $errCount++;
                }
            }
            if ($errCount > 0) {
                $this->messageManager->addError(__('A total of %1 record(s) cannot be removed.', $errCount));
            }
            if ($count > 0) {
                $this->messageManager->addSuccess(__('A total of %1 record(s) have been removed.', $count));
            }
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/group/index');
    }
}
