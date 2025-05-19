<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_MpVendorRegistration
 * @author    Webkul
 * @copyright Copyright (c)  Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\MpVendorRegistration\Observer;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Filesystem;

/**
 * Webkul MpVendorRegistration AdminhtmlCustomerPrepareSaveAfterObserver Observer.
 */
class AdminhtmlCustomerPrepareSaveAfterObserver implements ObserverInterface
{
    /**
     * File Uploader factory.
     *
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $fileUploaderFactory;

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * Store manager.
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    private $messageManager;

    protected $mediaDirectory;

    /**
     * @var \Magento\Framework\Json\DecoderInterface
     */
    protected $jsonDecoder;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    protected $request;

    /**
     * @var \Webkul\MpVendorRegistration\Helper\Data
     */
    protected $currentHelper;

    /**
     * Undocumented function
     *
     * @param Filesystem $filesystem
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param DataObjectHelper $dataObjectHelper
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     * @param \Webkul\MpVendorRegistration\Helper\Data $currentHelper
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\Json\DecoderInterface $jsonDecoder
     */
    public function __construct(
        Filesystem $filesystem,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        DataObjectHelper $dataObjectHelper,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Webkul\MpVendorRegistration\Helper\Data $currentHelper,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Json\DecoderInterface $jsonDecoder
    ) {
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->fileUploaderFactory = $fileUploaderFactory;
        $this->productRepository = $productRepository;
        $this->objectManager = $objectManager;
        $this->messageManager = $messageManager;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->storeManager = $storeManager;
        $this->currentHelper = $currentHelper;
        $this->date = $date;
        $this->request = $request;
        $this->jsonDecoder = $jsonDecoder;
    }

    /**
     * admin customer save after event handler.
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $event = $observer->getEvent();
        $customer = $event->getCustomer();
        $customData = [];
        try {
            $path = $this->mediaDirectory->getAbsolutePath(
                'wkmpvrfiles/'
            );

            $files = $this->request->getFiles();
            $formData = $this->request->getParams();

            $customDataNew = [];
            foreach ($formData['customer'] as $key => $value) {
                if (strpos($key, 'wkmpvr') !== false) {
                    $customDataNew[$key] = $value;
                }
            }

            $allowedImageExtensions = explode(',', $this->currentHelper->getConfigData('allowed_image_extension'));
            $allowedFileExtensions = explode(',', $this->currentHelper->getConfigData('allowed_file_extension'));
            $allowedExtensions = array_merge($allowedImageExtensions, $allowedFileExtensions);
            foreach ($files as $attrCode => $value) {
                if (strpos($attrCode, 'wkmpvr') !== false) {
                    if ($value['error'] == 0) {
                        $uploader = $this->fileUploaderFactory->create(['fileId' => $attrCode]);
                        $uploader->setAllowedExtensions($allowedExtensions);
                        $uploader->setAllowRenameFiles(true);
                        $uploader->setFilesDispersion(false);
                        $result = $uploader->save($path);
                        $customDataNew[$attrCode] = $result['file'];
                    } else {
                        $customDataNew[$attrCode] = $customDataNew[$attrCode];
                    }
                }
            }
            $this->dataObjectHelper->populateWithArray(
                $customer,
                $customDataNew,
                \Magento\Customer\Api\Data\CustomerInterface::class
            );
        } catch (\Exception $e) {
            $this->logger->debug("AdminhtmlCustomerPrepareSaveAfterObserver exception : " . $e->getMessage());
        }
    }
}
