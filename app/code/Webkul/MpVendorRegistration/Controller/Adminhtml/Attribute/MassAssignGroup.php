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

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Webkul\MpVendorRegistration\Model\ResourceModel\VendorRegistrationAttribute\CollectionFactory;

/**
 * Class AssignGroup
 */
class MassAssignGroup extends \Magento\Backend\App\Action
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var Webkul\MpVendorRegistration\Model\VendorRegistrationAssignGroupFactory
     */
    protected $assignModelFactory;
    /**
     * Undocumented function
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param \Webkul\MpVendorRegistration\Model\VendorRegistrationAssignGroupFactory $assignModelFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter,
        CollectionFactory $collectionFactory,
        \Webkul\MpVendorRegistration\Model\VendorRegistrationAssignGroupFactory $assignModelFactory
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->assignModelFactory = $assignModelFactory;
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
            'Webkul_MpVendorRegistration::index'
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
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $groupId = $this->getRequest()->getParam('entity_id');
        $count = 0;
        $errCount = 0;
        // try {
        foreach ($collection as $item) {
            $assignModel = $this->assignModelFactory->create();
            $assignCollection = $assignModel->getCollection()
                ->addFieldToFilter('attribute_id', ['eq' => $item->getId()]);
            if ($assignCollection->getSize()) {
                foreach ($assignCollection as $assignCollectionData) {
                    $assignGroup = $this->assignModelFactory->create()->load($assignCollectionData->getId());
                    $assignGroup->setGroupId($groupId);
                    $assignGroup->save();
                    $count++;
                }
            }
            // else {
            //     $assignCollection = $assignCollection->addFieldToFilter('group_id', ['eq' => $groupId]);
            //     if (!$assignCollection->getSize()) {
            //         $assignModel->setAttributeId($item->getId());
            //         $assignModel->setGroupId($groupId);
            //         $assignModel->save();
            //         $count++;
            //     } else {
            //         $errCount++;
            //     }
            // }
        }
            // if ($errCount > 0) {
            //     $this->messageManager->addError(__('A total of %1 record(s) cannot be assigned.', $errCount));
            // }
        if ($count > 0) {
            $this->messageManager->addSuccess(__('A total of %1 record(s) have been assigned.', $count));
        }
            
        // } catch (\Exception $e) {
        //     $this->messageManager->addError($e->getMessage());
        // } catch (\Magento\Framework\Exception\LocalizedException $e) {
        //     $this->messageManager->addError($e->getMessage());
        // }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
