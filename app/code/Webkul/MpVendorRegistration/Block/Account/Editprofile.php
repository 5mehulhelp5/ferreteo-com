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
namespace Webkul\MpVendorRegistration\Block\Account;

/**
 * Webkul MpVendorRegistration Account Editprofile Block
 */

use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Filesystem\Driver\File;

class Editprofile extends \Magento\Framework\View\Element\Template
{
    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var Session
     */
    protected $customerSession;
    
    /**
     * @var \Webkul\MpVendorRegistration\Helper\Data
     */
    protected $currentHelper;
    
    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $customerFactory;

    protected $timezoneInterface;

    protected $mpHelper;

    protected $eavConfig;
    /**
     * @var File
     */
    protected $fileSystem;
    /**
     * @var Magento\Framework\Json\Helper\Data
     */
    protected $jsonData;
    protected $resolverInterface;
    protected $file;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Webkul\MpVendorRegistration\Helper\Data $currentHelper
     * @param CustomerFactory $customerFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Customer\Model\Session $customerSession,
        \Webkul\MpVendorRegistration\Helper\Data $currentHelper,
        CustomerRepositoryInterface $customerRepository,
        CustomerFactory $customerFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezoneInterface,
        \Webkul\Marketplace\Helper\Data $mpHelper,
        \Magento\Framework\Json\Helper\Data $jsonData,
        \Magento\Framework\Locale\ResolverInterface $resolverInterface,
        \Magento\Customer\Block\Adminhtml\Form\Element\File $file,
        File $fileSystem,
        \Magento\Eav\Model\Config $eavConfig,
        array $data = []
    ) {
        $this->objectManager = $objectManager;
        $this->customerSession = $customerSession;
        $this->currentHelper = $currentHelper;
        $this->fileSystem = $fileSystem;
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->timezoneInterface = $timezoneInterface;
        $this->jsonData = $jsonData;
        $this->mpHelper = $mpHelper;
        $this->resolverInterface = $resolverInterface;
        $this->file = $file;
        $this->eavConfig = $eavConfig;
        parent::__construct($context, $data);
    }
    /**
     * Get Attribute Collection
     * @param  boolean $isSeller
     * @return collection
     */
    public function getAttributeCollection($isSeller = false)
    {
        return $this->currentHelper->getAttributeCollectionFrontend($isSeller);
    }

    /**
     * Get Attribute Collection
     * @param  boolean $isSeller
     * @return collection
     */
    public function getAttributeCollectionByGroup($groupId, $isSeller = false)
    {
        $attributeData =  $this->currentHelper->getAttributeCollectionByGroup($isSeller);
        if (isset($attributeData['groups'])) {
            if (array_key_exists($groupId, $attributeData['groups'])) {
                return $attributeData['groups'][$groupId];
            }
        }
        return false;
    }

    /**
     * Get System configuration value.
     * @param  string $field
     * @return string
     */
    public function getConfigData($field)
    {
        return $this->currentHelper->getConfigData($field);
    }
    /**
     * Customer Data
     * @param  string $customerId
     * @return object
     */
    public function _loadCustomer($customerId = '')
    {
        if ($customerId == '') {
            $customerId = $this->customerSession->getCustomer()->getId();
        }
        $customerData = $this->customerFactory->create()->load($customerId);
        return $customerData;
    }
    
    /**
     * Get Current store
     * @return object
     */
    public function getStore()
    {
        return $this->currentHelper->getStore();
    }
    /**
     * Get Seller Profile details.
     * @return array.
     */
    public function getSellerDetails()
    {
        $shopUrl = $this->mpHelper->getProfileUrl();
        if (!$shopUrl) {
            $shopUrl = $this->getRequest()->getParam('shop');
        }
        if ($shopUrl) {
            $data = $this->mpHelper->getSellerCollectionObjByShop($shopUrl);
            foreach ($data as $seller) {
                return $seller;
            }
        }
    }
    /**
     * get The date format
     *
     * @param [type] $type
     * @return formated date
     */
    public function getDateFormat($type = \IntlDateFormatter::SHORT)
    {
        return (new \IntlDateFormatter(
            $this->resolverInterface->getLocale(),
            $type,
            \IntlDateFormatter::NONE
        ))->getPattern();
    }
    /**
     * get image Field
     *
     * @param [type] $value
     * @return file
     */
    public function getImageField($value)
    {
        $fileAttribute = $this->file;
        $fileAttribute->setValue($value);
        return $fileAttribute->getElementHtml();
    }
    /**
     * get custom media url
     *
     * @param [type] $attributeValue
     * @return url
     */
    public function getCustomerMediaUrl($attributeValue)
    {
        return $this->getUrl('pub/media', ['wkmpvrfiles' => $attributeValue]);
    }
    /**
     * covert date format on time zone
     *
     * @param [type] $date
     * @return date
     */
    public function convertDateFormat($date)
    {
        return $this->timezoneInterface->date($date)->format("Y-m-d");
    }
    /**
     * get attribute label
     *
     * @param [type] $attrCode
     * @return attribute
     */
    public function getAttrLabel($attrCode)
    {
        return $this->eavConfig->getAttribute('customer', $attrCode);
    }
    /**
     * @return marketplaceHelper
     */
    public function getMarketplaceHelper()
    {
        return $this->mpHelper;
    }
    /**
     * @return vendor registration helper
     */
    public function currentHelper()
    {
        return $this->currentHelper;
    }
    /**
     * @return file
     */
    public function getFileOpen()
    {
        return $this->fileSystem;
    }
    /**
     * @return json helper
     */
    public function getJsonHelper()
    {
        return $this->jsonData;
    }
}
