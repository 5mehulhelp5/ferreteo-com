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

namespace Mageplaza\ProductFinder\Block\Adminhtml\Grid;

use Magento\Backend\Block\Widget\Button\SplitButton;
use Magento\Backend\Block\Widget\Container;
use Magento\Backend\Block\Widget\Context;
use Mageplaza\ProductFinder\Model\Grid\RuleMode;

/**
 * Class Rule
 * @package Mageplaza\ProductFinder\Block\Adminhtml\Grid
 */
class Rule extends Container
{
    /**
     * @var RuleMode
     */
    protected $_ruleMode;

    /**
     * Rule constructor.
     *
     * @param Context $context
     * @param RuleMode $ruleMode
     * @param array $data
     */
    public function __construct(
        Context $context,
        RuleMode $ruleMode,
        array $data = []
    ) {
        $this->_ruleMode = $ruleMode;
        parent::__construct($context, $data);
    }

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->removeButton('add');
    }

    /**
     * @return Container
     */
    protected function _prepareLayout()
    {
        $addButtonProps = [
            'id'           => 'add_new_rule',
            'label'        => __('Add New Product Finder'),
            'class'        => 'add',
            'button_class' => '',
            'class_name'   => SplitButton::class,
            'options'      => $this->_getAddRuleButtonOptions(),
        ];
        $this->buttonList->add('add_new', $addButtonProps);

        return parent::_prepareLayout();
    }

    /**
     * @return array
     */
    protected function _getAddRuleButtonOptions()
    {
        $splitButtonOptions = [];
        $modes              = $this->_ruleMode->getRuleMode();
        foreach ($modes as $modeId => $modeLabel) {
            $splitButtonOptions[$modeId] = [
                'label'   => $modeLabel,
                'onclick' => "setLocation('" . $this->_getRuleCreateUrl($modeId) . "')",
                'default' => RuleMode::MODE_AUTO === $modeId,
            ];
        }

        return $splitButtonOptions;
    }

    /**
     * @param string $mode
     *
     * @return string
     */
    protected function _getRuleCreateUrl($mode = '')
    {
        return $this->getUrl('mpproductfinder/*/new', ['mode' => $mode]);
    }
}
