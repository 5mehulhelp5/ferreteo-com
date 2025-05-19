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

namespace Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Config\Model\Config\Source\Enabledisable;
use Magento\Framework\Data\Form;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Framework\Registry;
use Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Renderer\Category;
use Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Renderer\FullUrl;
use Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Renderer\Snippet;
use Mageplaza\ProductFinder\Model\Config\Source\Position as PositionType;
use Mageplaza\ProductFinder\Model\Config\Source\Template as TemplateType;
use Mageplaza\ProductFinder\Model\Rule;

/**
 * Class General
 * @package Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab
 */
class General extends Generic implements TabInterface
{
    /**
     * @var Enabledisable
     */
    protected $statusOptions;

    /**
     * @var TemplateType
     */
    protected $_templateType;

    /**
     * @var PositionType
     */
    protected $_positionType;

    /**
     * General constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Enabledisable $statusOptions
     * @param TemplateType $templateType
     * @param PositionType $positionTYpe
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Enabledisable $statusOptions,
        TemplateType $templateType,
        PositionType $positionTYpe,
        array $data = []
    ) {
        $this->statusOptions = $statusOptions;
        $this->_templateType = $templateType;
        $this->_positionType = $positionTYpe;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return Generic
     * @throws LocalizedException
     */
    protected function _prepareForm()
    {
        $model = $this->getCurrentRule();

        /** @var Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('mpproductfinder_');
        $form->setFieldNameSuffix('mpproductfinder');
        $fieldset = $form->addFieldset('base_fieldset', [
            'legend' => __('General'),
            'class'  => 'fieldset-wide'
        ]);

        if ($model->getId()) {
            $fieldset->addField('rule_id', 'hidden', ['name' => 'rule_id']);
        }

        $fieldset->addField('name', 'text', [
            'name'     => 'name',
            'label'    => __('Name'),
            'title'    => __('Name'),
            'required' => true
        ]);

        $fieldset->addField('status', 'select', [
            'name'   => 'status',
            'label'  => __('Status'),
            'title'  => __('Status'),
            'values' => $this->statusOptions->toOptionArray()
        ]);

        $fieldset->addField('template', 'select', [
            'name'   => 'template',
            'label'  => __('Finder Layout'),
            'title'  => __('Finder Layout'),
            'values' => $this->_templateType->toOptionArray(),
            'note'   => __('The filters of a finder can be arranged vertically or horizontally.')
        ]);

        $fieldset->addField('mode', 'hidden', ['name' => 'mode']);

        $fieldset->addField('position', 'select', [
            'name'               => 'position',
            'label'              => __('Position'),
            'title'              => __('Position'),
            'values'             => $this->_positionType->toOptionArray(),
            'after_element_html' => $this->getDependPositionHtml()
        ]);

        $fieldset->addField('result_url', 'text', [
            'name'     => 'result_url',
            'label'    => __('Page Finder Route'),
            'title'    => __('Page Finder Route'),
            'required' => true,
            'class'    => 'validate-alphanum'
        ]);

        if ($model->getId()) {
            $fieldset->addField('full_url', FullUrl::class, [
                'name'  => 'result_url',
                'label' => __('Full URL'),
                'title' => __('Full URL')
            ]);
        }

        $fieldset->addField('page_title', 'text', [
            'name'     => 'page_title',
            'label'    => __('Page Title'),
            'title'    => __('Page Title'),
            'required' => true
        ]);

        $categoryHtml = $this->getLayout()->createBlock(Template::class)
            ->setTemplate('Mageplaza_ProductFinder::form/renderer/category.phtml')
            ->toHtml();

        $fieldset->addField('categories_ids', Category::class, [
            'name'          => 'categories_ids',
            'label'         => __('Categories'),
            'title'         => __('Categories'),
            'category_html' => $categoryHtml
        ]);

        if (!$model->getCategoriesIds()) {
            $model->setCategoriesIds($model->getCategoryIds());
        }

        $fieldset->addField('sort_order', 'text', [
            'name'  => 'sort_order',
            'label' => __('Sort Order'),
            'title' => __('Sort Order'),
            'class' => 'validate-digits'
        ]);

        if ($model->getId()) {
            $model->setData('full_url', $this->getFullUrl($model));
            $subFieldset = $form->addFieldset('sub_fieldset', [
                'legend' => __('Another way to add finder to your page'),
                'class'  => 'fieldset-wide'
            ]);

            $html = $this->getLayout()->createBlock(Template::class)
                ->setTemplate('Mageplaza_ProductFinder::form/renderer/snippet.phtml')
                ->toHtml();

            $subFieldset->addField('snippet', Snippet::class, [
                'name'         => 'snippet',
                'label'        => __('How to use'),
                'title'        => __('How to use'),
                'snippet_html' => $html
            ]);
        }

        if (!$model->getId()) {
            $model->setData('mode', $this->getRequest()->getParam('mode'));
        }
        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @return string
     */
    public function getDependPositionHtml()
    {
        $value = PositionType::MAIN_SIDEBAR;
        $label = '';
        foreach ($this->_positionType->toOptionArray() as $item) {
            if ($item['value'] === $value) {
                $label = $item['label']->getText();
            }
        }

        $html = '<script>
    require(["jquery"], function ($) {
            "use strict";
            
            var template = $("#mpproductfinder_template"),
                position = $("#mpproductfinder_position");

            if (template.val() === "horizontal") {
                position.find($(\'option[value="' . $value . '"]\')).remove();
            }
            
            template.on("change", function() {
                if (template.val() === "horizontal") {
                    position.find($(\'option[value="' . $value . '"]\')).remove();
                } else {
                    position.append(\'<option value="' . $value . '">' . $label . '</option>\');
                }
            })
        });
</script>';

        return $html;
    }

    /**
     * @return mixed
     */
    public function getCurrentRule()
    {
        return $this->_coreRegistry->registry('mpproductfinder_rule');
    }

    /**
     * @param Rule $model
     *
     * @return string
     */
    public function getFullUrl($model)
    {
        $url = $this->getBaseUrl();
        $url .= $model->getResultUrl();

        return $url;
    }

    /**
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * @return Phrase|string
     */
    public function getTabLabel()
    {
        return __('General');
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
