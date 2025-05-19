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

use Magento\Backend\App\Action;
use Magento\Customer\Model\AttributeMetadataDataProviderFactory;
use Magento\Framework\Controller\ResultFactory;

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

    protected $vendorRegistrationGroupFactory;

    protected $session;

    protected $eavEntity;

    /**
     * Undocumented function
     *
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Customer\Model\AttributeFactory $attributeFactory
     * @param AttributeMetadataDataProviderFactory $attributeMetaData
     * @param \Webkul\MpVendorRegistration\Model\VendorRegistrationGroupFactory $vendorRegistrationGroupFactory
     * @param \Magento\Backend\Model\Session $session
     * @param \Magento\Eav\Model\Entity $eavEntity
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Customer\Model\AttributeFactory $attributeFactory,
        AttributeMetadataDataProviderFactory $attributeMetaData,
        \Webkul\MpVendorRegistration\Model\VendorRegistrationGroupFactory $vendorRegistrationGroupFactory,
        \Magento\Backend\Model\Session $session,
        \Magento\Eav\Model\Entity $eavEntity
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $registry;
        $this->attributeMetaData = $attributeMetaData;
        $this->attributeFactory = $attributeFactory;
        $this->vendorRegistrationGroupFactory = $vendorRegistrationGroupFactory;
        $this->session = $session;
        $this->eavEntity = $eavEntity;
        parent::__construct($context);
    }

    /**
     * Check for is allowed.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_MpVendorRegistration::group');
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
        $resultPage->setActiveMenu('Webkul_MpVendorRegistration::group')
            ->addBreadcrumb(__('Vendorgroup'), __('Vendorgroup'))
            ->addBreadcrumb(__('Manage Vendor Group'), __('Manage Vendor Group'));

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
        $this->entityTypeId = $this->eavEntity->setType(
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
        $id = $this->getRequest()->getParam('id');

        $vendorModel = $this->vendorRegistrationGroupFactory->create();
        if ($id) {
            $vendorModel->load($id);
            if (!$vendorModel->getId()) {
                $this->messageManager->addError(__('This group no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/index');
            }
        }
        // set entered data if was error when we do save
        $data = $this->session->getAttributeData(true);
        if (!empty($data)) {
            $vendorModel->addData($data);
        }
        $this->coreRegistry->register('vendor_group', $vendorModel);

        $item = $id ? __('Edit Vendor Group') : __('New Vendor Group');

        $resultPage = $this->resultPageFactory->create();

        $resultPage->getConfig()->getTitle()->prepend($id ? $vendorModel->getGroupName() : __('New Group'));

        $block = \Webkul\MpVendorRegistration\Block\Adminhtml\Group\Edit::class;
        $content = $resultPage->getLayout()->createBlock($block);
        $resultPage->addContent($content);
        $block = \Webkul\MpVendorRegistration\Block\Adminhtml\Group\Edit\Tabs::class;
        $left = $resultPage->getLayout()->createBlock($block);
        $resultPage->addLeft($left);
        return $resultPage;
    }
}
