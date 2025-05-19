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

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Data\Form;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Renderer\FilterOptions;

/**
 * Class Filters
 * @package Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab
 */
class Filters extends Generic implements TabInterface
{
    /**
     * @return Generic
     * @throws LocalizedException
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('mpproductfinder_rule');

        /** @var Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('filter_');
        $fieldset = $form->addFieldset('base_fieldset', [
            'legend' => __('Filters & Options'),
            'class'  => 'fieldset-wide'
        ]);

        $fieldset->addField('filter_options', 'text', [
            'class' => 'no-display'
        ]);

        $field = $fieldset->addField('render_filter', 'text', [
            'label' => 'Filters',
            'name'  => 'filter_options'
        ]);

        $renderer = $this->getLayout()->createBlock(FilterOptions::class);
        /** @var Form\Element\Renderer\RendererInterface $renderer */
        $field->setRenderer($renderer);

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @return Phrase|string
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
        return __('Filters');
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
