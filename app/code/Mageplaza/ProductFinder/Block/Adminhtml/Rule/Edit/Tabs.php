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

namespace Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit;

use Exception;
use Magento\Backend\Block\Widget;
use Magento\Backend\Block\Widget\Tabs as CoreTabs;

/**
 * Class Tabs
 * @package Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit
 */
class Tabs extends CoreTabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('mpproductfinder_rule_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Product Finder Information'));
    }

    /**
     * @return Widget
     * @throws Exception
     */
    protected function _beforeToHtml()
    {
        $this->addTab('general', [
            'label'   => __('General'),
            'title'   => __('General'),
            'content' => $this->getChildHtml('general'),
            'active'  => true
        ]);

        $this->addTab('filters', [
            'label'   => __('Filters & Options'),
            'title'   => __('Filters & Options'),
            'content' => $this->getChildHtml('filters')
        ]);

        $ruleId = $this->_request->getParam('rule_id');

        if ($ruleId) {
            $this->addTab('products', [
                'label'   => __('Filtered Products'),
                'title'   => __('Filtered Products'),
                'content' => $this->getChildHtml('products')
            ]);

            $this->addTab('promoted', [
                'label'   => __('Promoted Products'),
                'title'   => __('Promoted Products'),
                'content' => $this->getChildHtml('promoted')
            ]);
        }

        return parent::_beforeToHtml();
    }
}
