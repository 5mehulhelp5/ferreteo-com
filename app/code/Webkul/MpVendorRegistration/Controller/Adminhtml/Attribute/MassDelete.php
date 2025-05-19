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
 * Class Delete
 */
class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    protected $attributeFactory;

    /**
     * Undocumented function
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param \Magento\Customer\Model\AttributeFactory $attributeFactory
     * @param \Webkul\MpVendorRegistration\Model\VendorRegistrationAssignGroup $vrag
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter,
        CollectionFactory $collectionFactory,
        \Magento\Customer\Model\AttributeFactory $attributeFactory,
        \Webkul\MpVendorRegistration\Model\VendorRegistrationAssignGroupFactory $vrag
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->attributeFactory = $attributeFactory;
        $this->vrag = $vrag;
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
        $attributeModel = $this->attributeFactory->create();
        $vraGroup = $this->vrag->create();
        $count = 0;
        try {
            foreach ($collection as $item) {
                $attributeId = $item->getId();
                $attributeModel->load($item->getAttributeId());
                if ($attributeModel->getIsUserDefined() == 0) {
                    $item->delete();
                } else {
                    $attributeModel->delete();
                    $item->delete();
                }
                $vraGroup->getCollection()
                ->addFieldToFilter("attribute_id", ["eq"=>$attributeId])->walk('delete');
                $count++;
            }
            $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $count));
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
