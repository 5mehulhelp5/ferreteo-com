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

namespace Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Renderer;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Renderer\Fieldset\Element;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute as ResourceAttribute;
use Magento\Framework\Exception\LocalizedException;
use Mageplaza\ProductFinder\Helper\Data;
use Mageplaza\ProductFinder\Model\Config\Source\DisplayType;
use Mageplaza\ProductFinder\Model\Config\Source\SortBy;
use Mageplaza\ProductFinder\Model\ResourceModel\Filter as ResourceFilter;

/**
 * Class FilterOptions
 * @package Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Renderer
 */
class FilterOptions extends Element
{
    /**
     * @var string
     */
    protected $_template = 'Mageplaza_ProductFinder::form/renderer/filters.phtml';

    /**
     * @var SortBy
     */
    protected $_sortBy;

    /**
     * @var DisplayType
     */
    protected $_displayType;

    /**
     * @var ResourceFilter
     */
    protected $resourceFilter;

    /**
     * @var ResourceAttribute
     */
    protected $resourceAttribute;

    /**
     * FilterOptions constructor.
     *
     * @param Context $context
     * @param SortBy $sortBy
     * @param DisplayType $displayType
     * @param ResourceFilter $resourceFilter
     * @param ResourceAttribute $resourceAttribute
     * @param array $data
     */
    public function __construct(
        Context $context,
        SortBy $sortBy,
        DisplayType $displayType,
        ResourceFilter $resourceFilter,
        ResourceAttribute $resourceAttribute,
        array $data = []
    ) {
        $this->_sortBy           = $sortBy;
        $this->_displayType      = $displayType;
        $this->resourceFilter    = $resourceFilter;
        $this->resourceAttribute = $resourceAttribute;
        parent::__construct($context, $data);
    }

    /**
     * @return array
     */
    public function getSortBy()
    {
        return $this->_sortBy->toOptionArray();
    }

    /**
     * @return array
     */
    public function getDisplayType()
    {
        return $this->_displayType->toOptionArray();
    }

    /**
     * @param null $ruleId
     *
     * @return array
     * @throws LocalizedException
     */
    public function getFilterByRuleId($ruleId = null)
    {
        return $this->resourceFilter->getFilterByRuleId($ruleId);
    }

    /**
     * @return string
     */
    public function getAjaxUrl()
    {
        return $this->getUrl('*/*/attribute', ['_current' => true]);
    }

    /**
     * @return string
     */
    public function getDeleteFilterUrl()
    {
        return $this->getUrl('*/*/deleteFilter', ['_current' => true]);
    }

    /**
     * @return string
     */
    public function getAddOptionsUrl()
    {
        return $this->getUrl('*/*/addOptions', ['_current' => true]);
    }

    /**
     * @return mixed
     */
    public function getMode()
    {
        return $this->getRequest()->getParam('mode');
    }

    /**
     * @param string $attrId
     *
     * @return mixed
     */
    public function getAttributeName($attrId)
    {
        return $this->resourceAttribute->load($attrId)->getFrontendLabel();
    }

    /**
     * @return string
     */
    public function getJsonData()
    {
        $data = [
            'ruleId'          => $this->getRequest()->getParam('rule_id'),
            'mode'            => $this->getMode(),
            'ajaxUrl'         => $this->getAjaxUrl(),
            'sortBy'          => $this->getSortBy(),
            'displayType'     => $this->getDisplayType(),
            'deleteFilterUrl' => $this->getDeleteFilterUrl()
        ];

        return Data::jsonEncode($data);
    }
}
