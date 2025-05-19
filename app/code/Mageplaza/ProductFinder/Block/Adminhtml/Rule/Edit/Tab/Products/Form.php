<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_ProductFinder
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Products;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Framework\Registry;
use Mageplaza\ProductFinder\Helper\Data as HelperData;
use Mageplaza\ProductFinder\Model\Rule;

/**
 * Class Form
 * @package Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Products
 */
class Form extends Generic
{
    /**
     * Rule instance
     *
     * @var Rule
     */
    protected $_rule;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * Form constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param HelperData $helperData
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        HelperData $helperData,
        array $data = []
    ) {
        $this->helperData = $helperData;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return Generic
     * @throws LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('mpproductfinder_');

        $fieldset = $form->addFieldset('products_fieldset', ['legend' => __('Products')]);
        $fieldset->addClass('ignore-validate');
        $fieldset->addField('mppf_custom_button', 'hidden', [
            'after_element_html' => $this->_addActionButtonHtml()
        ]);

        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @return string
     */
    protected function _addActionButtonHtml()
    {
        $mode = $this->getRuleObject()->getMode();
        $html = $this->helperData->createButton('mppf-delete-all-product', __('Reset'), 'secondary');
        if ($mode === 'auto') {
            $html .= $this->helperData->createButton('mppf-generate-product', __('Index Products'));
        }

        if ($mode === 'manual') {
            $html .= $this->helperData->createButton('mppf-add-product', __('Add Product'));
            $html .= $this->helperData->createButton('mppf-import-product', __('Import Product'));
        }

        return $html;
    }

    /**
     * @return Rule|mixed
     */
    protected function getRuleObject()
    {
        if ($this->_rule === null) {
            return $this->_coreRegistry->registry('mpproductfinder_rule');
        }

        return $this->_rule;
    }

    /**
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * @return Phrase
     */
    public function getTabLabel()
    {
        return __('Products');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }
}
