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

use Exception;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Helper\Data;
use Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory;
use Mageplaza\ProductFinder\Model\Rule;

/**
 * Class Attribute
 * @package Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Renderer
 */
class Attribute extends Extended
{
    /**
     * Rule instance
     *
     * @var Rule
     */
    protected $_rule;

    /**
     * @var AttributeFactory
     */
    protected $attributeFactory;

    /**
     * Attribute constructor.
     *
     * @param Context $context
     * @param Data $backendHelper
     * @param AttributeFactory $attributeFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        AttributeFactory $attributeFactory,
        array $data = []
    ) {
        $this->attributeFactory = $attributeFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setId('mpproductfinder-attribute');
        $this->setDefaultSort('attribute_id');
        $this->setUseAjax(true);
    }

    /**
     * @return Extended
     */
    protected function _prepareCollection()
    {
        $collection = $this->attributeFactory->create()->getCollection();

        $collection->getSelect()->join(
            ['cea' => $collection->getTable('catalog_eav_attribute')],
            'main_table.attribute_id = cea.attribute_id',
            ['main_table.attribute_id']
        )->where('cea.is_filterable = 1 and main_table.frontend_input != "price"');

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return Extended
     * @throws Exception
     */
    protected function _prepareColumns()
    {
        $this->addColumn('attribute_id', [
            'header_css_class' => 'a-center',
            'type'             => 'radio',
            'html_name'        => 'attribute_id',
            'align'            => 'center',
            'index'            => 'attribute_id'
        ]);

        $this->addColumn('attribute_code', [
            'header'   => __('Attribute Code'),
            'index'    => 'attribute_code',
            'type'     => 'text',
            'sortable' => true,
        ]);

        $this->addColumn('frontend_label', [
            'header'   => __('Default Label'),
            'index'    => 'frontend_label',
            'type'     => 'text',
            'sortable' => true,
        ]);

        return parent::_prepareColumns();
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/attribute', ['_current' => true]);
    }
}
