<?php

namespace Ibnab\BankInfo\Block\System\Config\Form\Field;

class Banks extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray {

    /**
     * Grid columns
     *
     * @var array
     */
    protected $_columns = [];

    /**
     * Enable the "Add after" button or not
     *
     * @var bool
     */
    protected $_addAfter = true;

    /**
     * Label of add button
     *
     * @var string
     */
    protected $_addButtonLabel;
    /**
     * Check if columns are defined, set template
     *
     * @return void
     */
    protected function _construct() {
        parent::_construct();
        $this->_addButtonLabel = __('Add Bank');
    }


    /**
     * Prepare to render
     *
     * @return void
     */
    protected function _prepareToRender() {
        $this->addColumn('bank', array('label' => __('YourBankName'),'size' => 30));
        $this->addColumn('description', array('label' => __('Description'),'size' => 10));
        $this->addColumn('additional1', array('label' => __('Additional1'),'size' => 10));
        $this->addColumn('additional2', array('label' => __('Additional2'),'size' => 10));
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Bank');
    }

}
