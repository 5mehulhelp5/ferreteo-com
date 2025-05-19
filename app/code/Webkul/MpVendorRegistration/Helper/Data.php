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

namespace Webkul\MpVendorRegistration\Helper;

use Magento\Customer\Model\AccountManagement;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerCollectionFactory;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Webkul\MpVendorRegistration\Model\ResourceModel\VendorRegistrationAssignGroup\CollectionFactory as VragCf;
use Webkul\MpVendorRegistration\Model\ResourceModel\VendorRegistrationAttribute\CollectionFactory as VraCf;

class Data extends \Magento\Framework\App\Helper\AbstractHelper implements ArgumentInterface
{

    /**
     * @var \Webkul\MpVendorRegistration\Model\VendorRegistrationGroupFactory
     */
    protected $vendorGroupFactory;

    /**
     * @var \Webkul\MpVendorRegistration\Model\VendorRegistrationAttributeFactory
     */
    protected $vendorAttributeFactory;

    /**
     * @var \Webkul\MpVendorRegistration\Model\VendorRegistrationAssignGroupFactory
     */
    protected $vendorAssignGroupFactory;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Attribute\CollectionFactory
     */
    protected $customerAttributeFactory;

    protected $customerCollectionFactory;

    protected $eavConfig;

    protected $countryCollectionFactory;

    protected $jsonHelper;

    protected $code = 'vendor_registration';

    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $session;
    /**
     * \Magento\Framework\View\Asset\RepositoryFactory $reposFactory
     */
    protected $reposFactory;
    /**
     * \Magento\Eav\Model\AttributeFactory $attributeFactory
     */
    protected $_attributeFactory;
    
    /**
     * Undocumented function
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param CustomerCollectionFactory $customerCollectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Directory\Block\Data $directoryBlock
     * @param \Webkul\Marketplace\Helper\Data $mpHelper
     * @param \Magento\Customer\Model\Session $session
     * @param \Webkul\MpVendorRegistration\Model\ResourceModel\VendorRegistrationGroup\CollectionFactory
     *  $vendorGroupFactory
     * @param VraCf $vendorAttributeFactory
     * @param VragCf $vendorAssignGroupFactory
     * @param \Magento\Customer\Model\ResourceModel\Attribute\CollectionFactory $customerAttributeFactory
     * @param \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Magento\Framework\View\Asset\RepositoryFactory $reposFactory
     * @param \Magento\Eav\Model\AttributeFactory $attributeFactory
     * @param \Magento\Eav\Model\Config $eavConfig
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        CustomerCollectionFactory $customerCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Directory\Block\Data $directoryBlock,
        \Webkul\Marketplace\Helper\Data $mpHelper,
        \Magento\Customer\Model\Session $session,
        \Webkul\MpVendorRegistration\Model\ResourceModel\VendorRegistrationGroup\CollectionFactory $vendorGroupFactory,
        VraCf $vendorAttributeFactory,
        VragCf $vendorAssignGroupFactory,
        \Magento\Customer\Model\ResourceModel\Attribute\CollectionFactory $customerAttributeFactory,
        \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\View\Asset\RepositoryFactory $reposFactory,
        \Magento\Eav\Model\AttributeFactory $attributeFactory,
        \Magento\Eav\Model\Config $eavConfig
    ) {
        $this->mpHelper = $mpHelper;
        $this->customerCollectionFactory = $customerCollectionFactory;
        $this->storeManager = $storeManager;
        $this->directoryBlock = $directoryBlock;
        $this->vendorGroupFactory = $vendorGroupFactory;
        $this->session = $session;
        $this->vendorAttributeFactory = $vendorAttributeFactory;
        $this->vendorAssignGroupFactory = $vendorAssignGroupFactory;
        $this->customerAttributeFactory = $customerAttributeFactory;
        $this->eavConfig = $eavConfig;
        $this->jsonHelper = $jsonHelper;
        $this->_attributeFactory = $attributeFactory;
        $this->reposFactory = $reposFactory;
        $this->countryCollectionFactory = $countryCollectionFactory;
        parent::__construct($context);
    }
    /**
     * get group collection
     *
     * @return groupCollection
     */
    public function getGroupCollection()
    {
        $grpAttribute = $this->vendorAssignGroupFactory->create()->getResource()
            ->getTable('mp_vendor_registration_assign_group');
        $availableGroups = $this->vendorGroupFactory->create();
        $availableGroups->getSelect()
        ->join(
            ['mvrag'=>$grpAttribute],
            "main_table.entity_id = mvrag.group_id"
        )
            ->columns('main_table.entity_id as mvra_group_id')
            ->where('group_status = 1')
            ->where('show_in_frontend = 1')
            ->group('mvrag.group_id')
            ->order('sort_order', 'ASC');
        return $availableGroups;
    }
    /**
     * get attribute collection
     *
     * @return attributecollection
     */
    public function getAttributeCollection()
    {
        $grpAttribute = $this->vendorAssignGroupFactory->create()->getResource()
            ->getTable('mp_vendor_registration_assign_group');
        $customerAttribute = $this->customerAttributeFactory->create()
            ->getResource()->getTable('eav_attribute');
        $availableAttributes = $this->vendorAttributeFactory->create();
        $availableAttributes->getSelect()
            ->joinLeft(
                ['mvrag' => $grpAttribute],
                "main_table.entity_id = mvrag.attribute_id"
            )
            ->joinLeft(
                ['ea' => $customerAttribute],
                'ea.attribute_id = main_table.attribute_id'
            )
            ->columns('main_table.attribute_id as mvra_attribute_id')
            ->columns('main_table.is_required as mvra_is_required')
            ->columns('main_table.attribute_code as mvra_attribute_code')
            ->columns('main_table.entity_id as mvra_entity_id')
            ->where('attribute_status = 1')
            ->order('sort_order', 'ASC');
        return $availableAttributes;
    }
    /**
     * get attribute collection frontend
     *
     * @return attributecollection
     */
    public function getAttributeCollectionFrontend()
    {
        $grpAttribute = $this->vendorAssignGroupFactory->create()->getResource()
            ->getTable('mp_vendor_registration_assign_group');
        $customerAttribute = $this->customerAttributeFactory->create()
            ->getResource()->getTable('eav_attribute');
        $availableAttributes = $this->vendorAttributeFactory->create();
        $availableAttributes->getSelect()
            ->joinLeft(
                ['mvrag' => $grpAttribute],
                "main_table.entity_id = mvrag.attribute_id"
            )
            ->joinLeft(
                ['ea' => $customerAttribute],
                'ea.attribute_id = main_table.attribute_id'
            )
            ->columns('main_table.attribute_id as mvra_attribute_id')
            ->columns('main_table.is_required as mvra_is_required')
            ->columns('main_table.attribute_code as mvra_attribute_code')
            ->columns('main_table.entity_id as mvra_entity_id')
            ->where('main_table.attribute_status = 1')
            ->where('main_table.attribute_code LIKE "%wkmpvr%"')
            ->where('main_table.show_in_front = 1')
            ->order('main_table.sort_order', 'ASC');
        return $availableAttributes;
    }
    /**
     * get group mapping collection
     *
     * @return availableAssignGroup
     */
    public function getGroupMappingCollection()
    {
        $availableAssignGroup = $this->vendorAssignGroupFactory->create()
            ->getCollection()
            ->addFieldToFilter('attribute_status', ['eq' => 1]);
        return $availableAssignGroup;
    }
    /**
     * Get the address group
     *
     * @return address data
     */
    public function getAddressGroup()
    {
        $getAddress = $this->vendorGroupFactory->create()
        ->addFieldToFilter('group_code', ['eq'=>'addressinfo']);
            $getAddress->getSelect()
            ->columns('main_table.entity_id as group_id')
            ->columns('main_table.entity_id as mvra_group_id')
            ->where('group_status = 1')
            ->where('show_in_frontend = 1')
            ->order('sort_order', 'ASC');
            return $getAddress;
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getAllData()
    {
        $groupCollection = $this->getGroupCollection();
        $attributeCollection = $this->getAttributeCollection();
        $getAddress = $this->getAddressGroup();
        foreach ($groupCollection as $key => $value) {
                $groups[] = [
                    'group_id' => $value->getMvraGroupId(),
                    'group_name' => __($value->getGroupName()),
                    'lower_group_name' => str_replace(' ', '_', strtolower($value->getGroupName())),
                    'group_code' => $value->getGroupCode(),
                ];
        }
        $checkAddressField = $this->getConfigData("show_address");
        if ($checkAddressField) {
            foreach ($getAddress as $key => $value) {
                $groups[] = [
                    'group_id' => $value->getMvraGroupId(),
                    'group_name' => __($value->getGroupName()),
                    'lower_group_name' => str_replace(' ', '_', strtolower($value->getGroupName())),
                    'group_code' => $value->getGroupCode(),
                ];
            }
        }
        foreach ($attributeCollection as $value) {
            $isRequiredArray = explode(' ', $value->getFrontendClass());
            $optiondata = [];
            if ($value->getFrontendInput() == 'select' || $value->getFrontendInput() == 'multiselect') {
                $attribute = $this->eavConfig->getAttribute('customer', $value->getMvraAttributeCode());
                $optiondata = $attribute->getSource()->getAllOptions();
                usort($optiondata, function ($a, $b) {
                    if (!empty($a['value']) && !empty($b['value'])) {
                        return $a['value'] - $b['value'];
                    }
                });
            }
            $extension = '';
            if ($value->getFrontendInput() == 'image') {
                $extension = $this->getConfigData('allowed_image_extension');
            }
            if ($value->getFrontendInput() == 'file') {
                $extension = $this->getConfigData('allowed_file_extension');
            }
            $attributLabel = $this->eavConfig->getAttribute('customer', $value->getMvraAttributeCode());

            if ($attributLabel->getStoreLabel() != null) {
                $frontendLabel = $attributLabel->getStoreLabel();
            } elseif ($value->getFrontendLabel() != null) {
                $frontendLabel = $value->getFrontendLabel();
            } else {
                $frontendLabel = $value->getAttributeLabel();
            }

            $attributes[] = [
                'attribute_id' => $value->getMvraEntityId(),
                'mvra_attribute_id' => $value->getMvraAttributeId(),
                'mvra_attribute_code' => $value->getMvraAttributeCode(),
                'frontend_input' => $value->getFrontendInput(),
                'frontend_label' => __($frontendLabel),
                'wysiwyg_enabled' => in_array('wysiwyg_enabled', $isRequiredArray) ? 1 : 0,
                'option_data' => $optiondata,
                'frontend_class' => $value->getFrontendClass() . ' validate-no-html-tags',
                'group_id' => $value->getGroupId(),
                'is_required' => $value->getMvraIsRequired(),
                'extension' => $extension,
                'shop_img' => $this->getViewFileUrl(),
                'attribute_by_admin' => $value->getAttributeByAdmin(),
            ];
        }
        foreach ($attributes as $key => $value) {
            foreach ($groups as $grp) {
                if ($grp['group_id'] == $value['group_id']) {
                    $attributes[$key]['lower_group_name'] = $grp['lower_group_name'];
                }
            }
        }
        if ($this->getMpGdprConfig('settings/active')) {
            $groups[] = [
                'group_id' => "gdpr99",
                'group_name' => __('GDPR Agreement'),
                'lower_group_name' => 'gdpr_agreement',
                'group_code' => 'gdpr_agreement',
            ];
        }
        $data['groups'] = $groups;
        $data['attributes'] = $attributes;
        $checkCustomerFieldsInData = $this->checkCustomerFieldsInData($data);
        return $checkCustomerFieldsInData;
    }
    /**
     * Undocumented function
     *
     * @param string $fileId
     * @param array $params
     * @return void
     */
    public function checkCustomerFieldsInData($data)
    {
        $customerAddressData = [];
        $customerAddressData["prefix"] =
        $this->getCustomerAddressScopeConfig("customer/address/prefix_show");
        $customerAddressData["suffix"] =
        $this->getCustomerAddressScopeConfig("customer/address/suffix_show");
        $customerAddressData["dob"] =
        $this->getCustomerAddressScopeConfig("customer/address/dob_show");
        $customerAddressData["middlename"] =
        $this->getCustomerAddressScopeConfig("customer/address/middlename_show");
        $customerAddressData["taxvat"] =
        $this->getCustomerAddressScopeConfig("customer/address/taxvat_show");
        $customerAddressData["gender"] =
        $this->getCustomerAddressScopeConfig("customer/address/gender_show");
        $customerAddressData["telephone"] =
        $this->getCustomerAddressScopeConfig("customer/address/telephone_show");
        $customerAddressData["company"] =
        $this->getCustomerAddressScopeConfig("customer/address/company");
        $customerAddressData["fax"] =
        $this->getCustomerAddressScopeConfig("customer/address/fax_show");

        foreach ($customerAddressData as $key => $value) {
            # code...
            foreach ($data["attributes"] as $i => &$j) {
                # code...
                if ($j["mvra_attribute_code"] == $key &&
                    ($customerAddressData[$key] == "req" || $customerAddressData[$key] == "opt")) {
                    if ($customerAddressData[$key] == "opt") {
                        $j["is_required"] = 0;
                    } elseif ($customerAddressData[$key] == 1) {
                        # code...
                        $j["is_required"] = 1;
                    } else {
                        $j["is_required"] = 1;
                    }
                } elseif ($j["mvra_attribute_code"] == $key &&
                    ($customerAddressData[$key] == "" || $customerAddressData[$key] == 0)) {
                    # code...
                    array_splice($data["attributes"], $i, 1);
                } elseif ($j["mvra_attribute_code"] == "created_at") {
                    array_splice($data["attributes"], $i, 1);
                }
            }
        }
        return $data;
    }
    /**
     * Undocumented function
     *
     * @param string $fileId
     * @param array $params
     * @return void
     */
    public function getViewFileUrl($fileId = 'Webkul_Marketplace::images/ajax-loader-tr.gif', array $params = [])
    {
        $this->_assetRepo = $this->reposFactory->create();
        $params = array_merge(['_secure' => $this->_request->isSecure()], $params);
        return $this->_assetRepo->getUrlWithParams($fileId, $params);
    }
    /**
     * Undocumented function
     *
     * @return boolean
     */
    public function isUserLoggedIn()
    {
        return $this->mpHelper->isCustomerLoggedIn();
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getBaseUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getCountryCollection()
    {
        $collection = $this->countryCollectionFactory->create()->loadByStore();
        return $collection;
    }

    /**
     * Retrieve list of top destinations countries
     *
     * @return array
     */
    protected function getTopDestinations()
    {
        $destinations = (string) $this->scopeConfig->getValue(
            'general/country/destinations',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return !empty($destinations) ? explode(',', $destinations) : [];
    }

    /**
     * Retrieve list of countries in array option
     *
     * @return array
     */
    public function getCountryHtmlSelect()
    {
        $options = $this->getCountryCollection()
            ->setForegroundCountries($this->getTopDestinations())
            ->toOptionArray();
        return $this->jsonHelper->jsonEncode($options);
    }
    /**
     * Undocumented function
     *
     * @param [type] $email
     * @return boolean
     */
    public function isUserExist($email)
    {
        $collection = $this->customerCollectionFactory->create();
        $collection->addFieldToFilter("email", $email);
        if ($collection->getSize()) {
            return true;
        }
        return false;
    }

    /**
     * Retrieve information from carrier configuration.
     *
     * @param string $field
     *
     * @return void|false|string
     */
    public function getConfigData($field)
    {
        if (empty($this->code)) {
            return false;
        }
        $path = 'vendor_registration_section/' . $this->code . '/' . $field;

        return $this->scopeConfig->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()
        );
    }
    /**
     * Retrieve information from carrier configuration.
     *
     * @param string $field
     *
     * @return void|false|string
     */
    public function getCustomerAddressScopeConfig($path)
    {
        return $this->scopeConfig->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()
        );
    }
    /**
     * Retrieve information from carrier configuration.
     *
     * @param string $field
     *
     * @return void|false|string
     */
    public function getMpGdprConfig($field)
    {
        return $this->scopeConfig->getValue(
            'mpgdpr/' . $field,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()
        );
    }

    /**
     * get agreement checked
     *
     * @return boolean
     */
    public function getConsent()
    {
        if ($this->session->isLoggedIn()) {
            $consent = $this->session->getCustomer()->getMpCustomerConsent();
            return $consent ? $consent : 0;
        } else {
            return 0;
        }
    }

    /**
     * Get current store
     * @return object
     */
    public function getStore()
    {
        return $this->storeManager->getStore();
    }
    /**
     * Undocumented function
     *
     * @param [type] $attrCode
     * @return void
     */
    public function getAllOptions($attrCode)
    {
        $attribute = $this->eavConfig->getAttribute('customer', $attrCode);
        return $attribute->getSource()->getAllOptions();
    }

    /**
     * Get minimum password length
     *
     * @return string
     * @since 100.1.0
     */
    public function getMinimumPasswordLength()
    {
        return $this->scopeConfig->getValue(AccountManagement::XML_PATH_MINIMUM_PASSWORD_LENGTH);
    }

    /**
     * Get number of password required character classes
     *
     * @return string
     * @since 100.1.0
     */
    public function getRequiredCharacterClassesNumber()
    {
        return $this->scopeConfig->getValue(AccountManagement::XML_PATH_REQUIRED_CHARACTER_CLASSES_NUMBER);
    }
    /**
     * Undocumented function
     *
     * @param [type] $attributeCode
     * @return void
     */
    public function getFieldFrontendInput($attributeCode)
    {
        $attributeCollection = $this->_attributeFactory->create()
            ->getCollection()
            ->addFieldToFilter('attribute_code', $attributeCode);

        if (count($attributeCollection) == 1) {
            foreach ($attributeCollection as $attribute) {
                return $attribute->getFrontendInput();
            }
        }
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getMarketplaceHelper()
    {
        return $this->mpHelper;
    }
}
