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

use Webkul\MpVendorRegistration\Model\VendorRegistrationAttribute;
use Webkul\MpVendorRegistration\Model\VendorRegistrationAssignGroupFactory;

class Save extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;

    /**
     * @var CollectionFactory
     */
    protected $assignFactory;

    /**
     *
     * @var attribute
     */
    protected $attribute;
    /**
     *
     * @var groupModel
     */
    protected $groupModel;
    /**
     *
     * @var session
     */
    protected $session;
    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $typeList;
    /**
     * @var config
     */
    protected $config;
    /**
     * Undocumented function
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param VendorRegistrationAssignGroupFactory $assignFactory
     * @param VendorRegistrationAttribute $attribute
     * @param \Webkul\MpVendorRegistration\Model\VendorRegistrationGroup $groupModel
     * @param \Magento\PageCache\Model\Config $config
     * @param \Magento\Framework\App\Cache\TypeListInterface $typeList
     * @param \Magento\Backend\Model\Session $session
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        VendorRegistrationAssignGroupFactory $assignFactory,
        VendorRegistrationAttribute $attribute,
        \Webkul\MpVendorRegistration\Model\VendorRegistrationGroup $groupModel,
        \Magento\PageCache\Model\Config $config,
        \Magento\Framework\App\Cache\TypeListInterface $typeList,
        \Magento\Backend\Model\Session $session
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->attribute = $attribute;
        $this->assignFactory = $assignFactory;
        $this->groupModel = $groupModel;
        $this->session = $session;
        $this->config = $config;
        $this->typeList = $typeList;
        parent::__construct($context);
    }
    /**
     * allow authorization
     *
     * @return authorization
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_MpVendorRegistration::group');
    }
    /**
     * Undocumented function
     *
     * @return redirected path
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if ($this->config->isEnabled()) {
            $this->typeList->invalidate(
                \Magento\PageCache\Model\Cache\Type::TYPE_IDENTIFIER
            );
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            $model = $this->groupModel;
            $id = $this->getRequest()->getParam('entity_id');
            if (!$id) {
                $groupName = $data['group_name'];
                /** validate the attribute code length and pre-existence **/
                if (!$this->validateGroupName($groupName)) {
                    return $resultRedirect->setPath('*/*/');
                }
            } else {
                $groupById = $model->load($id);
                if ($groupById->getGroupByAdmin() && $data['group_status'] == 0) {
                    $this->messageManager->addError(__('You cannot disable Admin created group'));
                    return $resultRedirect->setPath('*/*/edit', ['id' => $groupById->getId(), '_current' => true]);
                }
            }
            $model->setData($data);
            try {
                $model->save();
               
                $this->messageManager->addSuccess(__('You saved this group'));
                $this->session->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the reason.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('entity_id')]);
        }
            return $resultRedirect->setPath('*/*/');
    }
    /**
     * Undocumented function
     *
     * @param [type] $groupName
     * @return true|false
     */
    public function validateGroupName($groupName)
    {
        $groupData = $this->groupModel->getCollection()->addFieldToFilter('group_name', $groupName);
        ;
        
        if ($groupData->getSize() > 0) {
            $this->messageManager->addError(__('The group already exists.'));
            return false;
        }

        return true;
    }
}
