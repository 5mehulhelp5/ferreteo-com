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

namespace Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Products;

use Exception;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Helper\Data;
use Mageplaza\ProductFinder\Helper\Data as HelperData;
use Mageplaza\ProductFinder\Model\ResourceModel\Filter as ResourceFilter;
use Mageplaza\ProductFinder\Model\ResourceModel\Product\Collection as ProductFilterCollection;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule\Collection;

/**
 * Class Grid
 * @package Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Products
 */
class Grid extends Extended
{
    /**
     * @var ProductFilterCollection
     */
    protected $productFilterCollection;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var ResourceFilter
     */
    protected $resourceFilter;

    /**
     * Grid constructor.
     *
     * @param Context $context
     * @param Data $backendHelper
     * @param ProductFilterCollection $productFilterCollection
     * @param HelperData $helperData
     * @param ResourceFilter $resourceFilter
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        ProductFilterCollection $productFilterCollection,
        HelperData $helperData,
        ResourceFilter $resourceFilter,
        array $data = []
    ) {
        $this->productFilterCollection = $productFilterCollection;
        $this->helperData              = $helperData;
        $this->resourceFilter          = $resourceFilter;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * Initialize grid
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setId('mpproductfinder_products_grid');
        $this->setUseAjax(true);
    }

    /**
     * @return Extended
     */
    protected function _prepareCollection()
    {
        /** @var Collection $collection */
        $collection = $this->productFilterCollection;

        $collection->addFieldToFilter('rule_id', $this->helperData->getRuleId());
        if ($this->getRequest()->getParam('mode') === 'auto') {
            $collection->getSelect()
                ->join(
                    ['eav_table' => $collection->getTable('eav_attribute_option_value')],
                    'main_table.filter_options = eav_table.option_id',
                    ['option_label' => 'eav_table.value']
                );
        }

        $this->setCollection($collection);
        $this->setUseAjax(true);

        return parent::_prepareCollection();
    }

    /**
     * @return Extended
     * @throws Exception
     */
    protected function _prepareColumns()
    {
        $filters = $this->resourceFilter->getFilterByRuleId($this->helperData->getRuleId());

        $this->addColumn('name', [
            'header' => __('Product Name'),
            'index'  => 'product_name',
            'type'   => 'text'
        ]);

        $this->addColumn('sku', [
            'header' => __('SKU'),
            'index'  => 'product_sku',
            'type'   => 'text'
        ]);

        foreach ($filters as $filter) {
            $this->addColumn('filter-' . $filter['filter_id'], [
                'header'   => $filter['name'],
                'index'    => 'filter_' . $filter['filter_id'],
                'sortable' => false,
                'filter'   => false
            ]);
        }

        $this->addColumn('action', [
            'header'    => __('Action'),
            'width'     => '50px',
            'type'      => 'action',
            'getter'    => 'getProductId',
            'actions'   => [
                [
                    'caption' => __('Delete'),
                    'url'     => [
                        'base'   => '*/rule/deleteProduct/rule_id/' . $this->helperData->getRuleId() . '/mode/' . $this->getRequest()->getParam('mode')
                    ],
                    'field'   => 'product_id',
                    'confirm' => __('Are you sure?')
                ]
            ],
            'filter'    => false,
            'sortable'  => false,
            'is_system' => true,
        ]);

        $this->addExportType('*/rule/exportProductsCsv', __('CSV'));
        $this->addExportType('*/rule/exportProductsXml', __('Excel XML'));

        return parent::_prepareColumns();
    }

    /**
     * {@inheritdoc}
     */
    public function getGridUrl()
    {
        return $this->getUrl('mpproductfinder/rule/products', ['_current' => true]);
    }
}
