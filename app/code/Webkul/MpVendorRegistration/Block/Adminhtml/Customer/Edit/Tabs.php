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
namespace Webkul\MpVendorRegistration\Block\Adminhtml\Customer\Edit;

use Magento\Customer\Controller\RegistryConstants;
use Magento\Ui\Component\Layout\Tabs\TabInterface;
use Magento\Backend\Block\Widget\Form;
use Magento\Customer\Model\CustomerFactory;
use Magento\Backend\Block\Widget\Form\Generic;
use Webkul\MpVendorRegistration\Model\ResourceModel\VendorRegistrationAttribute\CollectionFactory;
use Magento\Framework\Filesystem\Driver\File;

/**
 * Customer account form block.
 */
class Tabs extends Generic implements TabInterface
{
    /**
     * @var string
     */
    /*protected $_template = 'customfields/customer/button.phtml';*/

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $systemStore;

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    protected $dob = null;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $customer;
    /**
     * Core registry.
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Attribute\CollectionFactory
     */
    protected $attributeCollection;

    /**
     * @var \Magento\Eav\Model\Entity
     */
    protected $eavEntity;
    /**
     * @var \Webkul\MpVendorRegistration\Helper\Data
     */
    protected $currentHelper;

    protected $sellerFactory;
    
    protected $attributeFactory;

    protected $vendorAttributeCollection;

    protected $formFactory;
    /**
     * @var File
     */
    protected $fileSystem;

    /**
     * Undocumented function
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Customer\Model\ResourceModel\Attribute\CollectionFactory $attributeCollection
     * @param \Magento\Eav\Model\Entity $eavEntity
     * @param CustomerFactory $customer
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Webkul\MpVendorRegistration\Helper\Data $currentHelper
     * @param \Webkul\Marketplace\Model\SellerFactory $sellerFactory
     * @param \Magento\Customer\Model\AttributeFactory $attributeFactory
     * @param File $fileSystem
     * @param CollectionFactory $vendorAttributeCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Customer\Model\ResourceModel\Attribute\CollectionFactory $attributeCollection,
        \Magento\Eav\Model\Entity $eavEntity,
        CustomerFactory $customer,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Webkul\MpVendorRegistration\Helper\Data $currentHelper,
        \Webkul\Marketplace\Model\SellerFactory $sellerFactory,
        \Magento\Customer\Model\AttributeFactory $attributeFactory,
        File $fileSystem,
        CollectionFactory $vendorAttributeCollection,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->systemStore = $systemStore;
        $this->attributeCollection = $attributeCollection;
        $this->customer = $customer;
        $this->eavEntity = $eavEntity;
        $this->fileSystem = $fileSystem;
        $this->objectManager = $objectManager;
        $this->storeManager = $context->getStoreManager();
        $this->currentHelper = $currentHelper;
        $this->sellerFactory = $sellerFactory;
        $this->attributeFactory = $attributeFactory;
        $this->vendorAttributeCollection = $vendorAttributeCollection;
        $this->formFactory = $formFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return string|null
     */
    public function getCustomerId()
    {
        return $this->coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID);
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Vendor Attribute Fields');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Vendor Attribute Fields');
    }

    /**
     * @return Webkul\Marketplace\Model\Seller
     */
    public function getMarketplaceUserCollection()
    {
        $customerId = $this->getRequest()->getParam('id');
        $collection = $this->sellerFactory->create()->getCollection()
                            ->addFieldToFilter('seller_id', $customerId);

        return $collection;
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        if (!$this->currentHelper->getConfigData('visible_registration')) {
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        $coll = $this->getMarketplaceUserCollection();
        $isSeller = false;
        foreach ($coll as $row) {
            $isSeller = $row->getIsSeller();
        }
        if ($this->getCustomerId() && $isSeller) {
            return false;
        }

        return true;
    }

    /**
     * Tab class getter.
     *
     * @return string
     */
    public function getTabClass()
    {
        return '';
    }

    /**
     * Return URL link to Tab content.
     *
     * @return string
     */
    public function getTabUrl()
    {
        return '';
    }

    /**
     * Tab should be loaded trough Ajax call.
     *
     * @return bool
     */
    public function isAjaxLoaded()
    {
        return false;
    }
    /**
     * create form
     *
     * @return form
     */
    public function _prepareForm()
    {
        if (!$this->canShowTab()) {
            return $this;
        }
        
        if (!empty($this->getCustomAttribute())) {
            $form = $this->formFactory->create();
            $form->setHtmlIdPrefix('customfields_');
            $customerId = $this->coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID);

            $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Vendor Information')]);

            $_customerData = $this->getCurrentCustomer()->toArray();

            foreach ($this->getCustomAttribute() as $record) {
                $fieldValue = '';
                $optiondata = [];
                $requiredStatus = false;
                $isRequired = '';
                if ($record->getIsRequired() != 0) {
                    $isRequired = 'wkrequired-entry';
                }

                if (!empty($_customerData)) {
                    foreach ($_customerData as $key => $value) {
                        $this->getFieldOptionValue($_customerData, $fieldValue, $optiondata, $record, $key, $value);
                    }
                }
                if ($record->getIsRequired() != 0) {
                    if ($record->getFrontendInput() == 'image' || $record->getFrontendInput() == 'file') {
                        if (!$fieldValue) {
                            $requiredStatus = true;
                        }
                    } else {
                        $requiredStatus = true;
                    }
                }
                if ($record->getFrontendInput() == 'multiselect' || $record->getFrontendInput() == 'select') {
                    $optiondata = $record->getSource()->getAllOptions();
                    usort($optiondata, function ($a, $b) {
                        if (!empty($a['value']) && !empty($b['value'])) {
                            return $a['value'] - $b['value'];
                        }
                    });
                }
                if ($record->getFrontendInput() == 'image') {
                    $url = $this->storeManager->getStore()->getBaseUrl(
                        \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                    );
                    $path = $url.'wkmpvrfiles/'.explode('/', $fieldValue)[count(explode('/', $fieldValue))-1];
                    $fieldset->addField(
                        $record->getAttributeCode(),
                        'image',
                        [
                            'name' => $record->getAttributeCode(),
                            'label' => __($record->getFrontendLabel()),
                            'title' => __($record->getFrontendLabel()),
                            'data' => $this->currentHelper->getConfigData('allowed_image_extension'),
                            'value' => $fieldValue ? $path :'',
                            'note' => __('Allowed image types:').' '.
                            $this->currentHelper->getConfigData('allowed_image_extension'),
                            'required' => $requiredStatus,
                            'after_element_html' => '<span class="data-extension webkul '.
                            $isRequired.'" data="'.$this->currentHelper
                            ->getConfigData('allowed_image_extension').'"></span>'
                        ]
                    );
                    $fieldset->addField(
                        'customer['.$record->getAttributeCode().']',
                        'hidden',
                        [
                            'name' => 'customer['.$record->getAttributeCode().']',
                            'value' => $fieldValue ? $fieldValue :'',
                            'data-form-part' => $this->getData('target_form')
                        ]
                    );
                } elseif ($record->getFrontendInput() == 'text') {
                    $fieldset->addField(
                        $record->getAttributeCode(),
                        'text',
                        [
                            'name' => 'customer['.$record->getAttributeCode().']',
                            'data-form-part' => $this->getData('target_form'),
                            'label' => __($record->getFrontendLabel()),
                            'title' => __($record->getFrontendLabel()),
                            'value' => $fieldValue,
                            'required' => $requiredStatus
                        ]
                    );
                } elseif ($record->getFrontendInput() == 'date') {
                    $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
                    $fieldset->addField(
                        $record->getAttributeCode(),
                        'date',
                        [
                            'name' => 'customer['.$record->getAttributeCode().']',
                            'data-form-part' => $this->getData('target_form'),
                            'class' => 'custom_date_field',
                            'label' => __($record->getFrontendLabel()),
                            'title' => __($record->getFrontendLabel()),
                            'value' => $fieldValue,
                            'input_format' => \Magento\Framework\Stdlib\DateTime::DATE_INTERNAL_FORMAT,
                            'date_format' => 'M/dd/Y',
                            'required' => $requiredStatus
                        ]
                    );
                } elseif ($record->getFrontendInput() == 'multiselect') {
                    $fieldset->addField(
                        $record->getAttributeCode(),
                        'multiselect',
                        [
                            'name' => $record->getAttributeCode(),
                            'label' => __($record->getFrontendLabel()),
                            'title' => __($record->getFrontendLabel()),
                            'values' => $optiondata,
                            'value' => $fieldValue,
                            'required' => $requiredStatus
                        ]
                    );
                } elseif ($record->getFrontendInput() == 'boolean') {
                    $fieldset->addField(
                        $record->getAttributeCode(),
                        'checkbox',
                        [
                            'name' => 'customer['.$record->getAttributeCode().']',
                            'data-form-part' => $this->getData('target_form'),
                            'label' => __($record->getFrontendLabel()),
                            'title' => __($record->getFrontendLabel()),
                            'onclick' => '',
                            'onchange' => 'this.value = this.checked?1:0',
                            'value' => $fieldValue,
                            'required' => $requiredStatus,
                            'checked' => $fieldValue ? true : false,
                        ]
                    );
                } elseif ($record->getFrontendInput() == 'select') {
                    $fieldset->addField(
                        $record->getAttributeCode(),
                        'select',
                        [
                            'name' => 'customer['.$record->getAttributeCode().']',
                            'data-form-part' => $this->getData('target_form'),
                            'label' => __($record->getFrontendLabel()),
                            'title' => __($record->getFrontendLabel()),
                            'values' => $optiondata,
                            'value' => $fieldValue,
                            'required' => $requiredStatus
                        ]
                    );
                } elseif ($record->getFrontendInput() == 'file') {
                    $path = "";
                    if ($fieldValue != "") {
                        $url = $this->storeManager->getStore()->getBaseUrl(
                            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                        );
                        $path = $url.'wkmpvrfiles/'.explode('/', $fieldValue)[count(explode('/', $fieldValue))-1];
                        $fileExists = $this->fileSystem->fileOpen($path, "r")? true : false;
                        if (!$fileExists) {
                            $path = $url.'wkmpvrfiles/'.explode('/', $fieldValue)[count(explode('/', $fieldValue))-1];
                        }
                    }
                    $fieldset->addField(
                        $record->getAttributeCode(),
                        'file',
                        [
                            'name' => $record->getAttributeCode(),
                            'data-form-part' => $this->getData('target_form'),
                            'label' => __($record->getFrontendLabel()),
                            'title' => __($record->getFrontendLabel()),
                            'required' => $requiredStatus,
                            'data' => $this->currentHelper->getConfigData('allowed_file_extension'),
                            'note' => __('Allowed file types:').' '.
                            $this->currentHelper->getConfigData('allowed_file_extension'),
                            'after_element_html' => $fieldValue != '' ?
                            '<a class="webkul '.$isRequired.'" target="_blank" href="'.$path.
                            '">'.__('Download').'</a><div style=float:right;margin-right:20%;">
                            <input data="'.$record->getAttributeCode().'" class="wkrm" type="checkbox" 
                            name="wkmpvr_'.$record->getAttributeCode().'[delete]"/> Remove</div><span 
                            class="data-extension" data="'.$this->currentHelper
                            ->getConfigData('allowed_file_extension').'">
                            </span>':'<span class="data-extension"
                             data="'.$this->currentHelper->getConfigData('allowed_file_extension').'"></span>',
                        ]
                    );
                    $fieldset->addField(
                        'customer['.$record->getAttributeCode().']',
                        'hidden',
                        [
                            'name' => 'customer['.$record->getAttributeCode().']',
                            'value' => $fieldValue ? $fieldValue :'',
                            'data-form-part' => $this->getData('target_form')
                        ]
                    );
                } else {
                    $type = 'textarea';
                    $config = '';
                    $frontClass = explode(' ', $record->getFrontendClass());
                    $fieldset->addField(
                        $record->getAttributeCode(),
                        $type,
                        [
                            'name' => 'customer['.$record->getAttributeCode().']',
                            'data-form-part' => $this->getData('target_form'),
                            'label' => __($record->getFrontendLabel()),
                            'title' => __($record->getFrontendLabel()),
                            'value' => $fieldValue,
                            'required' => $requiredStatus,
                            'config' => $config
                        ]
                    );
                }
            }
            $this->setForm($form);
            $form->setUseContainer(true);
        }

        return parent::_prepareForm();
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->canShowTab()) {
            $this->_prepareForm();
            return parent::_toHtml();
        } else {
            return '';
        }
    }

    /**
     * Prepare the layout.
     *
     * @return $this
     */
    public function getFormHtml()
    {
        $html = parent::getFormHtml();
        $html .= $this->getLayout()->createBlock(
            \Webkul\MpVendorRegistration\Block\Adminhtml\Customer\Edit\Button::class
        )->toHtml();

        return $html;
    }
    /**
     * get custom attribute
     *
     * @return attributecollection
     */
    public function getCustomAttribute()
    {
        $typeId = $this->eavEntity->setType('customer')->getTypeId();
        $customField = $this->vendorAttributeCollection->create()->getTable('mp_vendor_registration_attribute');
        $collection = $this->attributeCollection->create()
                ->setEntityTypeFilter($typeId);
        $collection->getSelect()->join(
            ['ccp' => $customField],
            'ccp.attribute_id = main_table.attribute_id'
        )
        ->where('ccp.attribute_code LIKE "%wkmpvr%"');
        
        return $collection;
    }
    /**
     * get current customer
     *
     * @return custerData
     */
    public function getCurrentCustomer()
    {
        $customerId = $customerId = $this->coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID);
        $customerData = $this->customer->create()->load($customerId);

        return $customerData;
    }
    /**
     * get option value in field
     */
    public function getFieldOptionValue($_customerData, &$fieldValue, &$optiondata, $record, $key, $value)
    {
        if ($record->getAttributeCode() == $key) {
            switch ($record->getFrontendInput()) {
                case 'image':
                    # code...
                    if ($value != null && !is_numeric($value)) {
                        $fieldValue = $value;
                    }
                    break;
                case 'date':
                    # code...
                    $fieldValue = $this->formatDate($value, \IntlDateFormatter::SHORT, false);
                    break;
                case 'boolean':
                    # code...
                    $fieldValue = $value;
                    break;
                case 'multiselect':
                    # code...
                    $fieldValue = $value;
                    $optiondata = $record->getSource()->getAllOptions();
                    usort($optiondata, function ($a, $b) {
                        if (!empty($a['value']) && !empty($b['value'])) {
                            return $a['value'] - $b['value'];
                        }
                    });
                    break;
                case 'select':
                    # code...
                    $fieldValue = $value;
                    $optiondata = $record->getSource()->getAllOptions();
                    usort($optiondata, function ($a, $b) {
                        if (!empty($a['value']) && !empty($b['value'])) {
                            return $a['value'] - $b['value'];
                        }
                    });
                    break;
                case 'file':
                    # code...
                    if ($value != null && !is_numeric($value)) {
                        $fieldValue = $value;
                    }
                    break;
                default:
                    # code...
                    $fieldValue = $value;
                    break;
            }
        }
    }
}
