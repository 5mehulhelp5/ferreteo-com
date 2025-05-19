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
use Webkul\MpVendorRegistration\Model\VendorRegistrationAssignGroupFactory;
use Webkul\MpVendorRegistration\Model\VendorRegistrationAttributeFactory;

/**
 * Class DisplayInFront
 */
class MassAssign extends \Magento\Backend\App\Action
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $assignFactory;

    /**
     * @var CollectionFactory
     */
    protected $attributeFactory;

    /**
     * Undocumented function
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Magento\Framework\Registry $registry
     * @param VendorRegistrationAssignGroupFactory $assignFactory
     * @param VendorRegistrationAttributeFactory $attributeFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\Framework\Registry $registry,
        VendorRegistrationAssignGroupFactory $assignFactory,
        VendorRegistrationAttributeFactory $attributeFactory
    ) {
        $this->filter = $filter;
        $this->assignFactory = $assignFactory;
        $this->attributeFactory = $attributeFactory;
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
        $group = $this->getRequest()->getParams();

        $groupid = $this->getRequest()->getParam('id');
        if ($groupid) {
            $attributeModel = $this->attributeFactory->create();
            $count = 0;
            try {
                if (isset($group['group'])) {
                    $model = $this->assignFactory->create();
                    foreach ($group['group'] as $id) {
                        $vendorAttribte = $this->attributeFactory->create()->load($id);
                        $attrId = $vendorAttribte->getAttributeId();
                        $modelCollection = $this->assignFactory->create()->getCollection()
                                           ->addFieldToFilter('attribute_id', $attrId)
                                           ->addFieldToFilter('group_id', ['eq' => $groupid]);
                        if (!$modelCollection->getSize()) {
                            $this->assignFactory->create()->setGroupId($groupid)
                                                ->setAttributeId($attrId)
                                                ->save();
                            $count++;
                        }
                    }
                }
                $this->messageManager->addSuccess(__('A total of %1 record(s) have been assigned.', $count));
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/group/edit', ['_current' => true, 'id' => $groupid]);
    }
}
