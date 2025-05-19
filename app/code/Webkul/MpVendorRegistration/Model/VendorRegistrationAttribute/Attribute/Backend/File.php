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
namespace Webkul\MpVendorRegistration\Model\VendorRegistrationAttribute\Attribute\Backend;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Customer\Controller\RegistryConstants;
use Magento\Framework\Filesystem\DriverInterface;

class File extends \Magento\Eav\Model\Entity\Attribute\Backend\Increment
{
    /**
     * @var string
     */
    protected $type = 'file';

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $uploaderFactory;

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
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    /**
     * Core registry.
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    protected $request;
    /**
     * @var \Webkul\MpVendorRegistration\Helper\Data
     */
    protected $currentHelper;

    /**
     * Construct.
     *
     * @param \Psr\Log\LoggerInterface                         $logger
     * @param \Magento\Framework\Filesystem                    $filesystem
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Webkul\MpVendorRegistration\Helper\Data $currentHelper
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Request\Http $request,
        \Webkul\MpVendorRegistration\Helper\Data $currentHelper,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
    ) {
        $this->filesystem = $filesystem;
        $this->coreRegistry = $registry;
        $this->request = $request;
        $this->fileUploaderFactory = $fileUploaderFactory;
        $this->logger = $logger;
        $this->currentHelper = $currentHelper;
    }
     /**
      * Save uploaded file and set its name to category
      *
      * @param \Magento\Framework\DataObject $object
      * @return \Magento\Catalog\Model\Category\Attribute\Backend\Image
      */
    public function beforeSave($object)
    {
        $attributeCode = $this->getAttribute()->getName();
        $value = $this->request->getPostValue();
        $explodedValue = explode('_', $attributeCode);
        if (is_array($explodedValue)) {
            if ('wkmpvr' == $explodedValue[0]) {
                $savedValue = '';
                
                if (isset($value['customer'][$attributeCode])) {
                    $savedValue = $value['customer'][$attributeCode];
                }
                if (isset($value['wkmpvr_'.$attributeCode]['delete'])
                              && $value['wkmpvr_'.$attributeCode]['delete'] == 1) {
                    $object->setData($this->getAttribute()->getName(), '');
                    $this->getAttribute()->getEntity()->saveAttribute($object, $this->getAttribute()->getName());
                    return $this;
                }
                if (isset($value[$attributeCode]['delete']) && $value[$attributeCode]['delete'] == 1) {
                    $object->setData($this->getAttribute()->getName(), '');
                    $this->getAttribute()->getEntity()->saveAttribute($object, $this->getAttribute()->getName());
                    return $this;
                }

                $path = $this->filesystem->getDirectoryRead(
                    DirectoryList::MEDIA
                )->getAbsolutePath(
                    'vendorfiles/'
                );
                if (is_array($value) && !empty($value['delete'])) {
                      $object->setData($this->getAttribute()->getName(), '');
                      $this->getAttribute()->getEntity()->saveAttribute($object, $this->getAttribute()->getName());
                      return $this;
                }
                $allowedExtensions = explode(',', $this->currentHelper
                                               ->getConfigData('allowed_'.$this->type.'_extension'));

                try {
                    /** @var $uploader \Magento\MediaStorage\Model\File\Uploader */
                    $uploader = $this->fileUploaderFactory->create(['fileId' => $this->getAttribute()->getName()]);
                    $uploader->setAllowedExtensions($allowedExtensions);
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);
                    $uploader->setAllowCreateFolders(true);
                    $result = $uploader->save($path);
                    $object->setData($this->getAttribute()->getName(), $result['file']);
                    $this->getAttribute()->getEntity()->saveAttribute($object, $this->getAttribute()->getName());
                } catch (\Exception $e) {
                    return $this->setPreviousImageIfNoImageSet($value, $savedValue, $object, $attributeCode);
                }
            }
        }
        return $this;
    }
    /**
     * @param $value
     * @param $savedValue
     * @param $object
     * @param $attributeCode
     * @return object
     */
    public function setPreviousImageIfNoImageSet($value, $savedValue, $object, $attributeCode)
    {
        if (isset($value['customer_edit'])) {
            return $this;
        }
             // if no image was set - save previous image value
        $filteredSavedValue = "";
        if (is_array($savedValue)) {
            $filteredSavedValue =  $savedValue[0]['file'];

            if (array_key_exists($attributeCode, $value) && is_array($value[$attributeCode])
                 && !isset($value[$attributeCode]['delete']) && $value[$attributeCode]['delete'] != 1) {
                if ($filteredSavedValue != '') {
                    $object->setData($this->getAttribute()->getName(), $filteredSavedValue);
                    $this->getAttribute()->getEntity()
                    ->saveAttribute($object, $this->getAttribute()->getName());
                }
            }
        } elseif ($savedValue != '') {
            $object->setData($this->getAttribute()->getName(), $savedValue);
            $this->getAttribute()->getEntity()->saveAttribute($object, $this->getAttribute()->getName());
        }
            return $this;
    }
}
