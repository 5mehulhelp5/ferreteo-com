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
namespace Webkul\MpVendorRegistration\Plugin\Customer\Model;

use Magento\Customer\Api\AddressMetadataInterface;
use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Api\Data\AttributeMetadataInterface;
use Magento\Customer\Model\FileProcessorFactory;
use Magento\Customer\Model\Metadata\ElementFactory;
use Webkul\MpVendorRegistration\Helper\Data;
use \Magento\Customer\Model\FileProcessor;

class FileUploader extends \Magento\Customer\Model\FileUploader
{

    protected $fileProcessorFactory;
    protected $entityTypeCode;
    protected $attributeMetadata;
    protected $scope;
    protected $helper;
    /**
     * Undocumented function
     *
     * @param CustomerMetadataInterface $customerMetadataService
     * @param AddressMetadataInterface $addressMetadataService
     * @param ElementFactory $elementFactory
     * @param FileProcessorFactory $fileProcessorFactory
     * @param AttributeMetadataInterface $attributeMetadata
     * @param Data $helper
     * @param string $entityTypeCode
     * @param string $scope
     */
    public function __construct(
        CustomerMetadataInterface $customerMetadataService,
        AddressMetadataInterface $addressMetadataService,
        ElementFactory $elementFactory,
        FileProcessorFactory $fileProcessorFactory,
        AttributeMetadataInterface $attributeMetadata,
        Data $helper,
        $entityTypeCode,
        $scope
    ) {
        $this->fileProcessorFactory = $fileProcessorFactory;
        $this->entityTypeCode = $entityTypeCode;
        $this->attributeMetaData = $attributeMetadata;
        $this->scope = $scope;
        $this->helper = $helper;
        parent::__construct(
            $customerMetadataService,
            $addressMetadataService,
            $elementFactory,
            $fileProcessorFactory,
            $attributeMetadata,
            $entityTypeCode,
            $scope
        );
    }
    /**
     * upload the file
     *
     * @return array
     */
    public function upload()
    {
        $attributeCode = $this->getAttrCode();

        /** @var FileProcessor $fileProcessor */
        $fileProcessor = $this->fileProcessorFactory->create([
            'entityTypeCode' => $this->entityTypeCode,
            'allowedExtensions' => $this->getAllowedExtensions(),
        ]);
        
        $result = $fileProcessor->saveTemporaryFile($this->scope . '[' . $this->getAttrCode() . ']');
        
        // Update tmp_name param. Required for attribute validation!
        // $result['tmp_name'] = $result['path'] . '/' . ltrim($result['file'], '/');
        $result['tmp_name'] = ltrim($result['file'], '/');

        $result['url'] = $fileProcessor->getViewUrl(
            FileProcessor::TMP_DIR . '/' . ltrim($result['name'], '/'),
            $this->helper->getFieldFrontendInput($attributeCode)
        );
        return $result;
    }
    /**
     * allowe extension get
     *
     * @return allowedExtensions
     */
    public function getAllowedExtensions()
    {
        $attributeCode = $this->getAttrCode();
        $frontendInput = $this->helper->getFieldFrontendInput($attributeCode);
        $allowedExtension = "";
        $allowedExtensions = [];

        if ($frontendInput == "file") {
            $allowedExtension = $this->helper->getAllowedFileExtensions();
        }
        if ($frontendInput == "image") {
            $allowedExtension = $this->helper->getAllowedImageExtensions();
        }

        $allowedExtensions = explode(',', $allowedExtension);
        array_walk($allowedExtensions, function (&$value) {
            $value = strtolower(trim($value));
        });
        
        return $allowedExtensions;
    }
    /**
     * get attr code
     *
     * @return attrCode
     */
    private function getAttrCode()
    {
        return $this->attributeMetaData->getAttributeCode();
    }
}
