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
namespace Webkul\MpVendorRegistration\Block\Adminhtml\Group\Edit\Tab;

use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Widget\Form\Generic;

/**
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 */
class Main extends Generic implements TabInterface
{
    /**
     * Adding product form elements for editing attribute
     *
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('vendor_group');
        /** @var DataForm $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' =>$this->getData('action'), 'method' => 'post']]
        );
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Group Information'), 'class' => 'fieldset-wide']
        );
        if ($model->getId()) {
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
        }
        $fieldset->addField('attr_ids', 'hidden', ['name' => 'attr_ids', 'id' => 'attr_ids']);
        $fieldset->addField('attr_ids_old', 'hidden', ['name' => 'attr_ids_old']);
        $fieldset->addField(
            'group_name',
            'text',
            [
                'name' => 'group_name',
                'label' => __('Group Name'),
                'title' => __('Group Name'),
                'required' => true,
                'class' => 'validate-no-html-tags'
            ]
        );
        $fieldset->addField(
            'sort_order',
            'text',
            [
                'label' => __('Sort Order'),
                'title' => __('Sort Order'),
                'name' => 'sort_order',
                'required' => true,
            ]
        );
        $fieldset->addField(
            'group_status',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'group_status',
                'required' => true,
                'options' => ['1' => __('Enabled'), '0' => __('Disabled')]
            ]
        );
        $data = $model->getData();
        $form->setValues($data);
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Group Details');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Group Details');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
}
