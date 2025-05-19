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
namespace Webkul\MpVendorRegistration\Controller\Seller;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Webkul\MpVendorRegistration\Model\ResourceModel\VendorRegistrationAttribute\Collection;

class Saveattribute extends \Magento\Customer\Controller\AbstractAccount
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var Session
     */
    protected $customerSession;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $attributeCollection;

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $eavEntity;
    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;
    /**
     * @var CustomerInterfaceFactory
     */
    protected $customerDataFactory;
    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;
     /**
      * @var \Magento\Customer\Model\Customer\Mapper
      */
    protected $customerMapper;

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    protected $vendorAttributeCollection;

    /**
     * Filesystem facade.
     *
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * File Uploader factory.
     *
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $fileUploaderFactory;

    /**
     * @var \Webkul\MpVendorRegistration\Helper\Data
     */
    protected $currentHelper;
    
    /**
     * Undocumented function
     *
     * @param Context $context
     * @param \Magento\Customer\Model\ResourceModel\Attribute\CollectionFactory $attributeCollection
     * @param CustomerInterfaceFactory $customerDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Magento\Eav\Model\Entity $eavEntity
     * @param \Magento\Customer\Model\Customer\Mapper $customerMapper
     * @param CustomerRepositoryInterface $customerRepository
     * @param \Magento\Customer\Model\Session $customerSession
     * @param Collection $vendorAttributeCollection
     * @param \Webkul\MpVendorRegistration\Helper\Data $currentHelper
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     * @param \Magento\Framework\Filesystem $filesystem
     */
    public function __construct(
        Context $context,
        \Magento\Customer\Model\ResourceModel\Attribute\CollectionFactory $attributeCollection,
        CustomerInterfaceFactory $customerDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Magento\Eav\Model\Entity $eavEntity,
        \Magento\Customer\Model\Customer\Mapper $customerMapper,
        CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Model\Session $customerSession,
        Collection $vendorAttributeCollection,
        \Webkul\MpVendorRegistration\Helper\Data $currentHelper,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\Filesystem $filesystem
    ) {
        $this->attributeCollection = $attributeCollection;
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->customerDataFactory = $customerDataFactory;
        $this->customerMapper = $customerMapper;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->eavEntity = $eavEntity;
        $this->vendorAttributeCollection = $vendorAttributeCollection;
        $this->fileUploaderFactory = $fileUploaderFactory;
        $this->currentHelper = $currentHelper;
        $this->filesystem = $filesystem;
        parent::__construct($context);
    }

    /**
     * save vendor attributes.
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $paramData = $this->getRequest();
        $customerId = $this->customerSession->getCustomerId();
        $typeId = $this->eavEntity->setType('customer')->getTypeId();

        $customField = $this->vendorAttributeCollection->getTable('mp_vendor_registration_attribute');

        $collection = $this->attributeCollection->create()
            ->setEntityTypeFilter($typeId);
        $collection->getSelect()
            ->join(
                ['ccp' => $customField],
                'ccp.attribute_id = main_table.attribute_id'
            );

        $error = [];
        $customData = $paramData->getPostValue();

        foreach ($collection as $attribute) {
            foreach ($customData as $attributeCode => $attributeValue) {
                if ($attributeCode==$attribute->getAttributeCode()) {
                    if ($attribute->getIsRequired()) {
                        if (empty($attributeValue)) {
                            $error[] = $attribute->getAttributeCode();
                        }
                    }
                }
            }
        }
        if (!empty($error)) {
            $this->messageManager->addError(__('Vendor Required Attributes can\'t be Empty.'));
        } else {
            $savedCustomerData = $this->customerRepository->getById($customerId);
            $saveData = $this->customerMapper->toFlatArray($savedCustomerData);

            $customer = $this->customerDataFactory->create();

            $customData = array_merge(
                $this->customerMapper->toFlatArray($savedCustomerData),
                $customData
            );
            $customData['id'] = $customerId;
            $path = $this->filesystem->getDirectoryRead(
                DirectoryList::MEDIA
            )->getAbsolutePath(
                'wkmpvrfiles/'
            );
            $allowedImageExtensions = explode(',', $this->currentHelper->getConfigData('allowed_image_extension'));
            $allowedFileExtensions = explode(',', $this->currentHelper->getConfigData('allowed_file_extension'));
            $allowedExtensions = array_merge($allowedImageExtensions, $allowedFileExtensions);
            $files = $this->getRequest()->getFiles();
            foreach ($files as $attrCode => $value) {
                if ($value['error'] == 0) {
                    $uploader = $this->fileUploaderFactory->create(['fileId' => $attrCode]);
                    $uploader->setAllowedExtensions($allowedExtensions);
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(false);
                    $result = $uploader->save($path);
                    $customData[$attrCode] = $result['file'];
                }
            }
            $this->dataObjectHelper->populateWithArray(
                $customer,
                $customData,
                \Magento\Customer\Api\Data\CustomerInterface::class
            );
            try {
                $this->customerRepository->save($customer);
                $this->messageManager->addSuccess(__('Vendor Attributes has been saved.'));
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        return $resultRedirect->setPath('marketplace/account/editprofile/');
    }
}
