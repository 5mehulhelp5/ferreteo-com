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

class Validate extends \Magento\Catalog\Controller\Adminhtml\Product\Attribute
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $layoutFactory;
     /**
      * @var string
      */
    protected $customerEntityTypeId;

    /**
     * @var \Magento\Eav\Model\Entity
     */
    protected $entityModel;

    /**
     * @var \Magento\Customer\Model\Attribute
     */
    protected $attributeModel;

    /**
     * Undocumented function
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Cache\FrontendInterface $attributeLabelCache
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     * @param \Magento\Eav\Model\Entity $entityModel
     * @param \Magento\Customer\Model\Attribute $attributeModel
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Cache\FrontendInterface $attributeLabelCache,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Eav\Model\Entity $entityModel,
        \Magento\Customer\Model\Attribute $attributeModel
    ) {
        parent::__construct($context, $attributeLabelCache, $coreRegistry, $resultPageFactory);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->layoutFactory = $layoutFactory;
        $this->entityModel = $entityModel;
        $this->attributeModel = $attributeModel;
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
     * Dispatch request.
     *
     * @param \Magento\Framework\App\RequestInterface $request
     *
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $this->customerEntityTypeId = $this->entityModel->setType(
            \Magento\Customer\Model\Customer::ENTITY
        )->getTypeId();

        return parent::dispatch($request);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $response = new \Magento\Framework\DataObject();
        $response->setError(false);

        $attributeCode = $this->getRequest()->getParam('attribute_code');
        
        $attributeId = $this->getRequest()->getParam('attribute_id');

        $attribute = $this->attributeModel->loadByCode(
            $this->customerEntityTypeId,
            $attributeCode
        );

        if ($attribute->getId() && !$attributeId) {
            if (strlen($this->getRequest()->getParam('attribute_code'))) {
                $response->setMessage(
                    __('An attribute with this code already exists.')
                );
            } else {
                $response->setMessage(
                    __('An attribute with the same code (%1) already exists.', $attributeCode)
                );
            }
            $response->setError(true);
        }
        return $this->resultJsonFactory->create()->setJsonData($response->toJson());
    }
}
