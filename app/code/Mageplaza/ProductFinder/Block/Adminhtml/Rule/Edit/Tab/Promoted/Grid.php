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

namespace Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Promoted;

use Exception;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Helper\Data;
use Magento\Catalog\Model\ProductFactory;
use Mageplaza\ProductFinder\Helper\Data as HelperData;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule\Collection;

/**
 * Class Grid
 * @package Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Promoted
 */
class Grid extends Extended
{
    /**
     * @var ProductFactory
     */
    protected $productFactory;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * Grid constructor.
     *
     * @param Context $context
     * @param Data $backendHelper
     * @param ProductFactory $productFactory
     * @param HelperData $helperData
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        ProductFactory $productFactory,
        HelperData $helperData,
        array $data = []
    ) {
        $this->productFactory = $productFactory;
        $this->helperData     = $helperData;
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

        $this->setId('mpproductfinder_promoted_grid');
        $this->setUseAjax(true);
    }

    /**
     * @return Extended
     */
    protected function _prepareCollection()
    {
        /** @var Collection $collection */
        $collection = $this->productFactory->create()->getCollection()->addFieldToSelect('name');
        $collection->getSelect()->joinRight(
            ['promoted_table' => $collection->getTable('mageplaza_productfinder_promoted_product')],
            'e.sku = promoted_table.product_sku',
            ['promoted_table.*']
        )->where('promoted_table.rule_id = ?', $this->helperData->getRuleId());

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Prepare grid columns
     *
     * @return Extended
     * @throws Exception
     */
    protected function _prepareColumns()
    {
        $this->addColumn('name', [
            'header' => __('Product Name'),
            'index'  => 'name',
            'type'   => 'text'
        ]);

        $this->addColumn('sku', [
            'header' => __('SKU'),
            'index'  => 'sku',
            'type'   => 'text'
        ]);

        $this->addColumn('action', [
            'header'    => __('Action'),
            'width'     => '50px',
            'type'      => 'action',
            'getter'    => 'getPromotedId',
            'actions'   => [
                [
                    'caption' => __('Delete'),
                    'url'     => [
                        'base'   => '*/rule/deletePromoted',
                        'params' => [
                            'rule_id' => $this->helperData->getRuleId(),
                            'mode'    => $this->getRequest()->getParam('mode')
                        ]
                    ],
                    'field'   => 'promoted_id',
                    'confirm' => __('Are you sure?')
                ]
            ],
            'filter'    => false,
            'sortable'  => false,
            'is_system' => true,
        ]);

        return parent::_prepareColumns();
    }

    /**
     * {@inheritdoc}
     */
    public function getGridUrl()
    {
        return $this->getUrl('mpproductfinder/rule/promoted', ['_current' => true]);
    }
}
