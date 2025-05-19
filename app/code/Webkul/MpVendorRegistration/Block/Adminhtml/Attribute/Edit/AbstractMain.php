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
namespace Webkul\MpVendorRegistration\Block\Adminhtml\Attribute\Edit;

abstract class AbstractMain extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Config\Model\Config\Source\YesnoFactory
     */
    protected $yesnoFactory;

    /**
     * @var \Magento\Eav\Model\Adminhtml\System\Config\Source\InputtypeFactory
     */
    protected $inputTypeFactory;
    /**
     * Attribute instance
     *
     * @var Attribute
     */
    protected $attribute = null;

    /**
     * @var \Magento\Eav\Block\Adminhtml\Attribute\PropertyLocker
     */
    protected $propertyLocker;

    /**
     * Eav data
     *
     * @var \Magento\Eav\Helper\Data
     */
    protected $eavData = null;

    /**
     * @var \Webkul\MpVendorRegistration\Model\VendorRegistrationGroupFactory
     */
    protected $vendorGroups;

    /**
     * @param \Magento\Eav\Helper\Data $eavData
     * @param \Magento\Config\Model\Config\Source\YesnoFactory $yesnoFactory
     * @param \Magento\Eav\Model\Adminhtml\System\Config\Source\InputtypeFactory $inputTypeFactory
     * @param \Magento\Eav\Block\Adminhtml\Attribute\PropertyLocker $propertyLocker
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param array $data
     * @codeCoverageIgnore
     */
    public function __construct(
        \Magento\Eav\Helper\Data $eavData,
        \Magento\Config\Model\Config\Source\YesnoFactory $yesnoFactory,
        \Magento\Eav\Model\Adminhtml\System\Config\Source\InputtypeFactory $inputTypeFactory,
        \Magento\Eav\Block\Adminhtml\Attribute\PropertyLocker $propertyLocker,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Webkul\MpVendorRegistration\Model\VendorRegistrationAttribute\Source\VendorGroups $vendorGroups,
        array $data = []
    ) {
        $this->propertyLocker = $propertyLocker;
        $this->eavData = $eavData;
        $this->yesnoFactory = $yesnoFactory;
        $this->inputTypeFactory = $inputTypeFactory;
        $this->vendorGroups = $vendorGroups;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Return attribute object
     *
     * @return Attribute
     */
    public function getattributeObject()
    {
        if (null === $this->attribute) {
            return $this->_coreRegistry->registry('entity_attribute');
        }
        return $this->attribute;
    }

    /**
     * Set attribute object
     *
     * @param Attribute $attribute
     * @return $this
     * @codeCoverageIgnore
     */
    public function setattributeObject($attribute)
    {
        $this->attribute = $attribute;
        return $this;
    }

    /**
     * Preparing default form elements for editing attribute
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        $attributeObj = $this->getattributeObject();
        $usedInForms = $attributeObj->getUsedInForms();
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );
        $form->setHtmlIdPrefix('customfields_');
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Attribute Properties'), 'collapsable' => true]
        );

        if ($attributeObj->getAttributeId()) {
            $fieldset->addField('attribute_id', 'hidden', ['name' => 'attribute_id']);
        }

        $this->_addElementTypes($fieldset);

        $yesno = $this->yesnoFactory->create()->toOptionArray();

        $validationClass = sprintf(
            'validate-code validate-length maximum-length-25'
        );

        $label = $attributeObj->getFrontendLabel();
        $fieldset->addField(
            'attribute_label',
            'text',
            [
                'name' => 'frontend_label[0]',
                'label' => __('Default Label'),
                'title' => __('Default Label'),
                'required' => true,
                'value' => is_array($label) ? $label[0] : $label,
                'class' => 'validate-no-html-tags',
            ]
        );

        $fieldset->addField(
            'attribute_code',
            'text',
            [
                'name' => 'attribute_code',
                'label' => __('Attribute Code'),
                'title' => __('Attribute Code'),
                'note' => __(
                    'Make sure you don\'t use spaces or more than 25 characters.'
                ),
                'class' => $validationClass,
                'required' => true,
            ]
        );

        $fieldset->addField(
            'frontend_input',
            'select',
            [
                'name' => 'frontend_input',
                'label' => __('Frontend Input Type'),
                'title' => __('Frontend Input Type'),
                'value' => 'text',
                'values' => $this->getFrontendInputType(),
            ]
        );

        $fieldset->addField(
            'is_required',
            'select',
            [
                'name' => 'is_required',
                'label' => __('Values Required'),
                'title' => __('Values Required'),
                'values' => $yesno,
            ]
        );

        if ($attributeObj->getFrontendInput() != 'date') {
            $fieldset->addField(
                'frontend_class',
                'select',
                [
                    'name' => 'frontend_class',
                    'label' => __('Input Validation'),
                    'title' => __('Input Validation'),
                    'values' => $this->eavData->getFrontendClasses($attributeObj->getEntityType()->getEntityTypeCode()),
                ]
            );
        }

        $fieldset->addField(
            'sort_order',
            'text',
            [
                'name' => 'sort_order',
                'label' => __('Input Field Sort Order'),
                'title' => __('Input Field Sort Order'),
                'required' => true,
                'class' => "validate-number",
            ]
        );

        $fieldset->addField(
            'assign_group',
            'select',
            [
                'name' => 'assign_group',
                'label' => __('Assign Group'),
                'title' => __('Assign Group'),
                'value' => $this->_coreRegistry->registry('attribute_group'),
                'values' => $this->vendorGroups->toOptionArray(),
            ]
        );

        $this->propertyLocker->lock($form);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Initialize form fileds values
     *
     * @return $this
     */
    protected function _initFormValues()
    {
        $this->_eventManager->dispatch(
            'adminhtml_block_eav_attribute_edit_form_init',
            ['form' => $this->getForm()]
        );
        $formData = $this->getattributeObject()->getData();
        $formData['frontend_class'] = trim(preg_replace('/required/', '', $formData['frontend_class']));

        $this->getForm()->addValues($formData);
        return parent::_initFormValues();
    }

    /**
     * Processing block html after rendering
     * Adding js block to the end of this block
     *
     * @param   string $html
     * @return  string
     */
    protected function _afterToHtml($html)
    {
        $jsScripts = $this->getLayout()->createBlock(\Magento\Eav\Block\Adminhtml\Attribute\Edit\Js::class)->toHtml();
        return $html . $jsScripts;
    }
    /**
     * Get the array list of the front end input type
     * @return front-end input type
     */
    protected function getFrontendInputType()
    {
        $frontendInputType = $this->inputTypeFactory->create()->toOptionArray();
        foreach ($frontendInputType as $key => &$value) {
            # code...
            if ($value["value"] == "datetime") {
                unset($frontendInputType[$key]);
            }
        }
        return $frontendInputType;
    }
}
