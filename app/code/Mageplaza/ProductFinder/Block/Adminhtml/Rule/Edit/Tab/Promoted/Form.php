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

namespace Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Promoted;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Data\Form as FormAlias;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Framework\Registry;
use Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Renderer\AddProduct;
use Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Renderer\ImportProduct;
use Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Renderer\ImportPromoted;
use Mageplaza\ProductFinder\Helper\Data as HelperData;
use Mageplaza\ProductFinder\Model\ResourceModel\Filter as ResourceFilter;

/**
 * Class Form
 * @package Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Promoted
 */
class Form extends Generic
{
    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var ResourceFilter
     */
    protected $resourceFilter;

    /**
     * Form constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param HelperData $helperData
     * @param ResourceFilter $resourceFilter
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        HelperData $helperData,
        ResourceFilter $resourceFilter,
        array $data = []
    ) {
        $this->helperData     = $helperData;
        $this->resourceFilter = $resourceFilter;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return Generic
     * @throws LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var FormAlias $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('mpproductfinder_');

        $fieldset = $form->addFieldset('promoted_fieldset', ['legend' => __('Promoted Products')]);
        $fieldset->addClass('ignore-validate');

        $fieldset->addField('sku_product', 'text', [
            'name'               => 'sku',
            'label'              => __('Add By SKU'),
            'title'              => __('Add By SKU'),
            'after_element_html' => $this->addSkuBtnHtml()
        ]);

        $fieldset->addField('custom_button', 'hidden', [
            'after_element_html' => $this->customBtnHtml()
        ]);

        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @return string
     */
    private function addSkuBtnHtml()
    {
        return '<button type="button" class="action-default secondary" id="mppf_add_product">'
            . __('Add') . '</button>';
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    private function customBtnHtml()
    {
        $html = '';
        $html .= $this->helperData->createButton(
            'mppf_import_promoted_product',
            __('Import by CSV'),
            'primary',
            'style="display: block; margin-bottom: 20px"'
        );
        $html .= $this->helperData->createButton('mppf_delete_all_promoted_product', __('Reset'), 'secondary');
        $html .= '<div id="mppf-import-promoted" style="display: none">'
            . $this->getImportPromotedFormHtml() . '</div>';
        $html .= '<div id="mppf-add-product-modal" style="display: none">' . $this->getAddProductFormHtml() . '</div>';
        $html .= '<div id="mppf-import-product-modal" style="display: none">'
            . $this->getImportProductFormHtml() . '</div>';
        $html .= '<script type="text/x-magento-init">
                    {
                        "#mpproductfinder_sku_product": {
                            "Mageplaza_ProductFinder/js/actions":{
                                "addUrl": "' . $this->getAddUrl() . '",
                                "deleteAllUrl": "' . $this->getDeleteAllUrl() . '",
                                "importUrl": "' . $this->getImportUrl() . '",
                                "deleteAllProductUrl": "' . $this->getDeleteAllProductUrl() . '",
                                "generateProductUrl": "' . $this->getGenerateProductUrl() . '",
                                "addProductUrl": "' . $this->getAddProductUrl() . '",
                                "importProductUrl": "' . $this->getImportProductUrl() . '",
                                "filters": ' . $this->getFilterByRuleId() . ',
                                "mode" : "' . $this->getRequest()->getParam('mode') . '"
                            }
                        }
                    }
                </script>';

        return $html;
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    private function getFilterByRuleId()
    {
        return HelperData::jsonEncode($this->resourceFilter->getFilterByRuleId($this->helperData->getRuleId()));
    }

    /**
     * @return string
     */
    private function getAddUrl()
    {
        return $this->getUrl('*/*/addPromoted', ['_current' => true]);
    }

    /**
     * @return string
     */
    private function getDeleteAllUrl()
    {
        return $this->getUrl('*/*/deleteAllPromoted', ['_current' => true]);
    }

    /**
     * @return string
     */
    private function getImportUrl()
    {
        return $this->getUrl('*/import/importPromoted', ['_current' => true]);
    }

    /**
     * @return string
     */
    private function getDeleteAllProductUrl()
    {
        return $this->getUrl('*/*/deleteAllProduct', ['_current' => true]);
    }

    /**
     * @return string
     */
    private function getGenerateProductUrl()
    {
        return $this->getUrl('*/*/generateProduct', ['_current' => true]);
    }

    /**
     * @return string
     */
    private function getAddProductUrl()
    {
        return $this->getUrl('*/*/addProduct', ['_current' => true]);
    }

    /**
     * @return string
     */
    private function getImportProductUrl()
    {
        return $this->getUrl('*/import/importProduct', ['_current' => true]);
    }

    /**
     * @return string
     */
    public function getImportPromotedFormHtml()
    {
        return $this->_layout->createBlock(ImportPromoted::class)->toHtml();
    }

    /**
     * @return string
     */
    public function getAddProductFormHtml()
    {
        return $this->_layout->createBlock(AddProduct::class)->toHtml();
    }

    /**
     * @return string
     */
    public function getImportProductFormHtml()
    {
        return $this->_layout->createBlock(ImportProduct::class)->toHtml();
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
        return __('Promoted Products');
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
