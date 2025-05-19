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
namespace Webkul\MpVendorRegistration\Block\Adminhtml\Group\Edit;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Data\Form as DataForm;

class Form extends Generic
{

    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('group_form');
        $this->setTitle(__('Vendor Group Information'));
    }
    /**
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var DataForm $form */
        $model = $this->_coreRegistry->registry('vendor_group');
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' =>$this->getData('action'), 'method' => 'post']]
        );
        $form->setUseContainer(true);
        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
