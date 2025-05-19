<?php
/**
 * @category   Webkul
 * @package    Webkul_MpSellerBadge
 * @author     Webkul Software Private Limited
 * @copyright  Copyright (c)  Webkul Software Private Limited (https://webkul.com)
 * @license    https://store.webkul.com/license.html
 */
namespace Webkul\MpSellerBadge\Block\Adminhtml\Badges\Edit;

/**
 * Adminhtml MpSellerBadge Badges Edit Form
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('badges_form');
        $this->setTitle(__('Badge Information'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('mpsellerbadge_badges');
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form',
                        'enctype' => 'multipart/form-data',
                        'action' => $this->getData('action'),
                        'method' => 'post'
                        ]
                    ]
        );
        $form->setHtmlIdPrefix('bades_');
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Badges Information'), 'class' => 'fieldset-wide']
        );
        if ($model->getId()) {
            $fieldset->addField('entity_id', 'hidden', ['name' => 'id']);
        }
        $fieldset->addField(
            'badge_name',
            'text',
            [
                'name' => 'badge_name',
                'label' => __('Badge Name'),
                'title' => __('Badge Name'),
                'required' => true ,
                'class' => 'validate-no-html-tags'
            ]
        );
        $fieldset->addField(
            'badge_description',
            'editor',
            [
                'name' => 'badge_description',
                'label' => __('Badge Description'),
                'title' => __('Badge Description'),
                'style' => 'height:10em',
                'required' => true,
                'class' => 'validate-no-html-tags'
            ]
        );
        $fieldset->addField(
            'badge_image_url',
            'image',
            [
                'name' => 'image',
                'label' => __('Image'),
                'title' => __('Image'),
                'class'     => 'required-entry required-file',
                'required' => true
            ]
        );
        $fieldset->addField(
            'rank',
            'text',
            [
                'name' => 'rank',
                'label' => __('Rank'),
                'title' => __('Rank'),
                'required' => true,
                'class' => 'validate-digits validate-no-html-tags'
            ]
        );
        $fieldset->addField(
            'status',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'status',
                'required' => true,
                'options' => ['1' => __('Enabled'), '0' => __('Disabled')]
            ]
        );
        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
