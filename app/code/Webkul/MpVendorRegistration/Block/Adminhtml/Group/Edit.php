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

namespace Webkul\MpVendorRegistration\Block\Adminhtml\Group;

/**
 * Product attribute edit page
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Block group name
     *
     * @var string
     */
    protected $_blockGroup = 'Webkul_MpVendorRegistration';

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'attribute_id';
        $this->_blockGroup = 'Webkul_MpVendorRegistration';
        $this->_controller = 'adminhtml_group';

        parent::_construct();

        $this->addButton(
            'save_and_edit_button',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ]
            ]
        );
        $this->buttonList->update('save', 'label', __('Save Group'));
        $this->buttonList->update('save', 'class', 'save primary');
    }

    /**
     * get edit model from registery
     * @return \Webkul\MpVendorRegistration\Model\VendorRegistrationGroup
     */
    public function getModel()
    {
        return $this->_coreRegistry->registry('vendor_group');
    }
    /**
     * Retrieve header text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('vendor_group')->getId()) {
            $frontendLabel = $this->_coreRegistry->registry('entity_attribute')->getFrontendLabel();
            if (is_array($frontendLabel)) {
                $frontendLabel = $frontendLabel[0];
            }
            return __('Edit Vendor Attribute "%1"', $this->escapeHtml($frontendLabel));
        }
        return __('New Vendor Attribute');
    }

     /**
      * Getter of url for "Save and Continue" button
      * tab_id will be replaced by desired by JS later
      *
      * @return string
      */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl(
            'vendorregistration/*/save',
            ['_current' => true, 'back' => 'edit']
        );
    }

    /**
     * Retrieve URL for save
     *
     * @return string
     */
    public function getSaveUrl()
    {
        return $this->getUrl(
            'vendorregistration/*/save',
            ['_current' => true, 'back' => null]
        );
    }
}
