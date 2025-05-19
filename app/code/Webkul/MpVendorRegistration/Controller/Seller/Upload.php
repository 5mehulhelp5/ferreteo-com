<?php
/**
 * Webkul Software
 *
 * @category  Webkul
 * @package   Webkul_MpVendorRegistration
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\MpVendorRegistration\Controller\Seller;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Action\Context;

class Upload extends Action
{

    /**
     * @var helper
     */
    protected $helper;
    /**
     * @var fileUploaderFactory
     */
    protected $fileUploaderFactory;
    /**
     * @var fileSystem
     */
    protected $fileSystem;
    
    /**
     * Undocumented function
     *
     * @param Context $context
     * @param \Webkul\MpVendorRegistration\Helper\Data $helper
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     * @param Filesystem $filesystem
     */
    public function __construct(
        Context $context,
        \Webkul\MpVendorRegistration\Helper\Data $helper,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        Filesystem $filesystem
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->fileUploaderFactory = $fileUploaderFactory;
        $this->helper = $helper;
        $this->fileSystem = $filesystem;
        parent::__construct($context);
    }
    /**
     * Undocumented function
     *
     * @return json data
     */
    public function execute()
    {
        $allowedTypes = $this->getRequest()->getParam('allowedTypes');
        $allowedTypes = explode(',', $allowedTypes);
        $result = [];
        $result["error"] = false;
        $result["msg"] = "Available";
        $allowedImageExtensions = explode(',', $this->helper->getConfigData('allowed_image_extension'));
        $allowedFileExtensions = explode(',', $this->helper->getConfigData('allowed_file_extension'));
        $allowedExtensions = array_merge($allowedImageExtensions, $allowedFileExtensions);
        
        $uploader = $this->fileUploaderFactory->create(['fileId' => 'image']);
        $uploader->setAllowedExtensions($allowedExtensions);
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilesDispersion(false);
        $path = $this->fileSystem->getDirectoryWrite(DirectoryList::MEDIA)->getAbsolutePath('wkmpvrfiles/');
        $imgResult = $uploader->save($path);
        
        $actualType = mime_content_type($imgResult['path'].$imgResult['file']);
        $type = explode('/', $actualType)[count(explode('/', $actualType))-1];
        if ($type == "msword") {
            $type = 'doc';
        }
        if ($type == "odt") {
            $type = 'doc';
        }
        if (!in_array($type, $allowedTypes)) {
            $result["error"] = true;
            $result["msg"] = __("File type not allowed");
        } elseif ($imgResult['error'] == 0) {
            $result["msg"] = __("File Uploaded Successfully");
            $result["data"] = $imgResult;
        } else {
            $result["error"] = true;
            $result["msg"] = __("Error Occured");
        }
    
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($result);
    }
}
