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

use Magento\Backend\App\Action;
use Magento\Customer\Model\AttributeMetadataDataProviderFactory;

class Edit extends \Magento\Backend\App\Action
{
    /**
     * Core registry.
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * @var string
     */
    protected $entityTypeId;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var AttributeMetadataDataProvider
     */
    private $attributeMetaData;

    protected $attributeFactory;

    /**
     * @var \Webkul\MpVendorRegistration\Model\VendorRegistrationAssignGroupFactory
     */
    protected $groupAssignFactory;

    protected $entityModel;

    protected $vendorAttributeFactory;

    protected $sessionModel;

    /**
     * Undocumented function
     *
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Customer\Model\AttributeFactory $attributeFactory
     * @param \Webkul\MpVendorRegistration\Model\VendorRegistrationAssignGroupFactory $groupAssignFactory
     * @param \Webkul\MpVendorRegistration\Model\VendorRegistrationAttributeFactory $vendorAttributeFactory
     * @param AttributeMetadataDataProviderFactory $attributeMetaData
     * @param \Magento\Eav\Model\Entity $entityModel
     * @param \Magento\Backend\Model\Session $sessionModel
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Customer\Model\AttributeFactory $attributeFactory,
        \Webkul\MpVendorRegistration\Model\VendorRegistrationAssignGroupFactory $groupAssignFactory,
        \Webkul\MpVendorRegistration\Model\VendorRegistrationAttributeFactory $vendorAttributeFactory,
        AttributeMetadataDataProviderFactory $attributeMetaData,
        \Magento\Eav\Model\Entity $entityModel,
        \Magento\Backend\Model\Session $sessionModel
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $registry;
        $this->attributeMetaData = $attributeMetaData;
        $this->attributeFactory = $attributeFactory;
        $this->groupAssignFactory = $groupAssignFactory;
        $this->entityModel = $entityModel;
        $this->vendorAttributeFactory = $vendorAttributeFactory;
        $this->sessionModel = $sessionModel;
        parent::__construct($context);
    }

    /**
     * Check for is allowed.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_MpVendorRegistration::index');
    }

    /**
     * Init actions.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Webkul_MpVendorRegistration::index')
            ->addBreadcrumb(__('Vendorattribute'), __('Vendorattribute'))
            ->addBreadcrumb(__('Manage Vendor Attribute'), __('Manage Vendor Attribute'));

        return $resultPage;
    }

    /**
     * Dispatch request.
     *
     * @param \Magento\Framework\App\RequestInterface $request
     *
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $this->entityTypeId = $this->entityModel->setType(
            \Magento\Customer\Model\Customer::ENTITY
        )->getTypeId();

        return parent::dispatch($request);
    }

    /**
     * Edit Custom fields page.
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $groups = [];
        $id = $this->getRequest()->getParam('id');

        $attributeCode = $this->getRequest()->getParam('attribute_code');

        $vendorModel = $this->vendorAttributeFactory->create();
        $attributeModel = $this->attributeFactory->create();
        /** @var $model \Magento\Catalog\Model\ResourceModel\Eav\Attribute */
        $model = $this->attributeFactory->create()->setEntityTypeId(
            $this->entityTypeId
        );
        $class = '';
        if ($id) {
            $vendorModel->load($id);
            $attributeId = $this->attributeMetaData
                        ->create()
                        ->getAttribute('customer', $attributeCode)
                        ->getAttributeId();

            $model->load($attributeId);

            $class = $model->getFrontendClass();
            $model->setIsVisible($vendorModel->getStatus());
            if (!$model->getId()) {
                $this->messageManager->addError(__('This attribute no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/vendorattribute/index');
            }

            // entity type check
            if ($model->getEntityTypeId() != $this->entityTypeId) {
                $this->messageManager->addError(__('This attribute cannot be edited.'));
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/vendorattribute/index');
            }

            $requiredCheck = $model->getFrontendClass();
            $require = explode(' ', $requiredCheck);
            if (in_array('required', $require)) {
                $model->setIsRequired(1);
                $model->setFrontendClass($require[0]);
            }
            /*
                If attribute assigned to groups
            */
            $groupAssignModel = $this->groupAssignFactory->create();
            $assignCollection = $groupAssignModel->getCollection()
                    ->addFieldToFilter('attribute_id', ['eq' => $id]);
            foreach ($assignCollection as $value) {
                $groups[] = $value->getGroupId();
            }
        }

        // set entered data if was error when we do save
        $data = $this->sessionModel->getAttributeData(true);
        if (!empty($data)) {
            $model->addData($data);
        }
        $attributeData = $this->getRequest()->getParam('attribute');
        if (!empty($attributeData) && $id === null) {
            $model->addData($attributeData);
        }
        $model->setFrontendClass($class);
        $this->coreRegistry->register('entity_attribute', $model);
        $this->coreRegistry->register('attribute_group', implode(',', $groups));
        
        $item = $id ? __('Edit Vendor Attribute') : __('New Vendor Attribute');

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend($id ? $model->getName() : __('New Attribute'));
        return $resultPage;
    }
}
