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

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('vendor_group_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Attribute Group Information'));
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        $block = \Webkul\MpVendorRegistration\Block\Adminhtml\Group\Edit\Tab\Main::class;
        $attrBlock = \Webkul\MpVendorRegistration\Block\Adminhtml\Group\Edit\Tab\Attributes::class;
        $this->addTab(
            'main',
            [
                'label' => __('Group Details'),
                'content' => $this->getLayout()->createBlock($block, 'main')->toHtml(),
            ]
        );
        return parent::_prepareLayout();
    }
}
