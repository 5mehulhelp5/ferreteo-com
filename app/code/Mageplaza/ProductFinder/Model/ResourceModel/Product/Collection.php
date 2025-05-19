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
 * @category  Mageplaza
 * @package   Mageplaza_ProductFinder
 * @copyright Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license   https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\ProductFinder\Model\ResourceModel\Product;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Mageplaza\ProductFinder\Helper\Data as HelperData;
use Mageplaza\ProductFinder\Model\Product;
use Mageplaza\ProductFinder\Model\ResourceModel\Options as ResourceOption;
use Mageplaza\ProductFinder\Model\ResourceModel\Product as ResourceFilterProduct;
use Psr\Log\LoggerInterface;

/**
 * Class Collection
 * @package Mageplaza\ProductFinder\Model\ResourceModel\Product
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'product_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'mageplaza_productfinder_filter_products_collection';

    /**
     * @var string
     */
    protected $_eventObject = 'filter_products_collection';

    /**
     * @var Http
     */
    protected $request;

    /**
     * @var ResourceOption
     */
    protected $resourceOption;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var array
     */
    protected $actionList = [
        'mpproductfinder_rule_edit',
        'mpproductfinder_rule_addProduct',
        'mpproductfinder_import_importProduct',
        'mpproductfinder_rule_products',
        'mpproductfinder_rule_deleteFilter',
        'mpproductfinder_rule_generateProduct',
        'mpproductfinder_rule_exportProductsCsv',
        'mpproductfinder_rule_exportProductsXml',
    ];

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Product::class, ResourceFilterProduct::class);
    }

    /**
     * Collection constructor.
     *
     * @param EntityFactoryInterface $entityFactory
     * @param LoggerInterface $logger
     * @param FetchStrategyInterface $fetchStrategy
     * @param ManagerInterface $eventManager
     * @param Http $request
     * @param ResourceOption $resourceOption
     * @param HelperData $helperData
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        Http $request,
        ResourceOption $resourceOption,
        HelperData $helperData
    ) {
        $this->request        = $request;
        $this->resourceOption = $resourceOption;
        $this->helperData     = $helperData;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager);
    }

    /**
     * @return array|null
     * @throws LocalizedException
     */
    public function getData()
    {
        $fullActName = $this->request->getFullActionName();
        $param       = $this->request->getParams();

        if (in_array($fullActName, $this->actionList, true)) {
            $this->_renderFilters()->_renderOrders()->_renderLimit();
            $select = $this->getSelect();
            $data   = $this->_fetchAll($select);

            if ($this->_data === null) {
                if ($param['mode'] === 'manual') {
                    foreach ($data as $value) {
                        $filterData = [
                            'product_id'   => $value['product_id'],
                            'product_name' => $value['product_name'],
                            'product_sku'  => $value['product_sku']
                        ];
                        $filterIds  = HelperData::jsonDecode($value['filter_ids']);
                        $options    = HelperData::jsonDecode($value['filter_options']);
                        $filterList = [];

                        foreach ($filterIds as $key => $filterId) {
                            foreach ($options as $keyOption => $option) {
                                $optionLabel = $this->resourceOption->getLabelByOptionId($option);
                                if ($key === $keyOption
                                    && $this->resourceOption->checkOptionByFilterId($option, $filterId)) {
                                    $filterList += [
                                        'filter_' . $filterId => $optionLabel
                                    ];
                                }
                            }
                        }

                        $filteredData = $this->mergeArray($filterData, $filterList);

                        $this->_data[] = $filteredData;
                    }
                    $this->_afterLoadData();
                } else {
                    $data = $this->helperData->sortArray($data, 'product_sku');
                    foreach ($data as $value) {
                        $this->_data[] = [
                            'product_id'                     => $value['product_id'],
                            'product_name'                   => $value['product_name'],
                            'product_sku'                    => $value['product_sku'],
                            'filter_' . $value['filter_ids'] => $value['option_label']
                        ];
                    }
                }

                return $this->_data;
            }
        }

        return parent::getData();
    }

    /**
     * @param array $filterData
     * @param array $filterList
     *
     * @return array
     */
    public function mergeArray($filterData, $filterList)
    {
        return array_merge($filterData, $filterList);
    }
}
