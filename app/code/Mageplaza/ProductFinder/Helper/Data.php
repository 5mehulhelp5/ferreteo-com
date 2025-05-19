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

namespace Mageplaza\ProductFinder\Helper;

use Magento\Catalog\Model\Product;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\Core\Helper\AbstractData;
use Mageplaza\ProductFinder\Model\ResourceModel\Product as ResourceProduct;
use Mageplaza\ProductFinder\Model\ResourceModel\Product\CollectionFactory as FilterProductCollection;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule as ResourceRule;

/**
 * Class Data
 * @package Mageplaza\ProductFinder\Helper
 */
class Data extends AbstractData
{
    const SAMPLE_PROMOTED_PRODUCTS_FILE_NAME = 'mpproductfinder_promoted_products_example.csv';
    const SAMPLE_PRODUCTS_FILE_NAME          = 'mpproductfinder_products_example.csv';
    const CONFIG_MODULE_PATH                 = 'mpproductfinder';

    /**
     * @var FilterProductCollection
     */
    protected $filterProductCollection;

    /**
     * @var ResourceConnection
     */
    protected $connection;

    /**
     * @var ResourceProduct
     */
    protected $resourceProduct;

    /**
     * @var ResourceRule
     */
    protected $resourceRule;

    /**
     * @var Product
     */
    protected $product;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     * @param StoreManagerInterface $storeManager
     * @param FilterProductCollection $filterProductCollection
     * @param ResourceConnection $connection
     * @param ResourceProduct $resourceProduct
     * @param ResourceRule $resourceRule
     * @param Product $product
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        FilterProductCollection $filterProductCollection,
        ResourceConnection $connection,
        ResourceProduct $resourceProduct,
        ResourceRule $resourceRule,
        Product $product
    ) {
        $this->filterProductCollection = $filterProductCollection;
        $this->connection              = $connection;
        $this->resourceProduct         = $resourceProduct;
        $this->resourceRule            = $resourceRule;
        $this->product                 = $product;
        parent::__construct($context, $objectManager, $storeManager);
    }

    /**
     * @return mixed
     */
    public function getRuleId()
    {
        return $this->_getRequest()->getParam('rule_id');
    }

    /**
     * @param string $ruleId
     *
     * @return array
     * @throws LocalizedException
     */
    public function getSkuByRuleId($ruleId)
    {
        $sku  = $this->filterProductCollection->create()
            ->distinct(true)
            ->addFieldToSelect('product_sku')
            ->addFieldToFilter('rule_id', $ruleId);
        $list = [];

        foreach ($sku->getData() as $item) {
            $list[] = $item['product_sku'];
        }

        return $list;
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    public function getSkuCollection()
    {
        $data          = $this->_getRequest()->getParams();
        $mode          = $this->resourceRule->getRuleById($data['rule_id'])['mode'];
        $dataArr       = [];
        $filterIds     = $mode === 'auto' ? [] : '';
        $filterOptions = $mode === 'auto' ? [] : '';
        $countFilter   = 0;

        if (count($data) <= 1) {
            return $dataArr;
        }

        foreach ($data as $key => $value) {
            $filterData = explode('-', $key);
            if ($value && $filterData[0] === 'filter') {
                $countFilter++;
            }
            if (count($filterData) > 1) {
                if ($mode === 'auto') {
                    $filterIds[]     = $filterData[1];
                    $filterOptions[] = $value;
                } else {
                    if ($value) {
                        $filterIds .= $filterData[1] . '%';
                    }
                    $filterOptions .= $value . '%';
                }
            }
        }

        $collection = $this->filterProductCollection->create();
        $collection->addFieldToFilter('rule_id', $data['rule_id']);

        if ($filterIds && $filterOptions) {
            if ($mode === 'manual') {
                $collection->addFieldToFilter('filter_ids', ['like' => '%' . $filterIds])
                    ->addFieldToFilter('filter_options', ['like' => '%' . $filterOptions]);
            } else {
                $collection->addFieldToFilter('filter_ids', $filterIds)
                    ->addFieldToFilter('filter_options', $filterOptions);
            }
        } else {
            return [];
        }

        $skuCollection = $collection->getData();

        if ($mode === 'auto') {
            $records       = [];
            $skuCollection = $this->sortArray($collection->getData(), 'product_sku');
            foreach ($skuCollection as $record) {
                $records[$record['product_sku']][] = $record['product_sku'];
            }

            foreach ($records as $duplicate) {
                if (count($duplicate) === $countFilter) {
                    $dataArr[] = $duplicate;
                }
            }

            return $dataArr;
        }

        foreach ($skuCollection as $data) {
            $dataArr[] = $data['product_sku'];
        }

        return $dataArr;
    }

    /**
     * @param string $sku
     * @param string $filterId
     *
     * @return array
     */
    public function checkExistProduct($sku, $filterId)
    {
        $connect = $this->connection->getConnection();
        $table   = $this->resourceProduct->getTable('mageplaza_productfinder_filter_product');
        $select  = $connect->select()
            ->from(['main' => $table])
            ->where('product_sku = ?', $sku)
            ->where('filter_id = ?', $filterId);

        return $connect->fetchAll($select);
    }

    /**
     * @param string $filterId
     * @param string $optionId
     *
     * @return array
     */
    public function checkOptionAvailable($filterId, $optionId)
    {
        $connect = $this->connection->getConnection();
        $table   = $this->resourceProduct->getTable('mageplaza_productfinder_filter_options');
        $select  = $connect->select()
            ->from(['main' => $table])
            ->where('filter_id = ?', $filterId)
            ->where('option_id = ?', $optionId);

        return $connect->fetchAll($select);
    }

    /**
     * @param string $sku
     * @param string $ruleId
     *
     * @return array
     */
    public function getProductDelete($sku, $ruleId)
    {
        $connect  = $this->connection->getConnection();
        $resource = $this->resourceProduct;
        $select   = $connect->select()
            ->from(
                ['main' => $resource->getTable('mageplaza_productfinder_filter_product')],
                'main.product_id'
            )
            ->join(
                ['mpf' => $resource->getTable('mageplaza_productfinder_filter')],
                'mpf.filter_id = main.filter_id'
            )->where('mpf.rule_id = ?', $ruleId)
            ->where('main.product_sku = ?', $sku);

        return $connect->fetchAll($select);
    }

    /**
     * @param array $data
     * @param mixed $field
     *
     * @return mixed
     */
    public function sortArray($data, $field)
    {
        $field = (array) $field;
        uasort($data, function ($a, $b) use ($field) {
            $value = 0;
            foreach ($field as $fieldName) {
                if ($value === 0) {
                    $value = strnatcmp($a[$fieldName], $b[$fieldName]);
                }
            }

            return $value;
        });

        return $data;
    }

    /**
     * @param string $route
     * @param string $ruleId
     * @param string $url
     *
     * @return mixed
     */
    public function customRouter($route, $ruleId, $url)
    {
        return str_replace($route . '/index/index/rule_id/' . $ruleId, $route, $url);
    }

    /**
     * @param string $sku
     *
     * @return array
     */
    public function getIdsBySku($sku)
    {
        $ids  = $this->product->getCollection()
            ->addFieldToFilter('sku', ['in' => $sku]);
        $list = [];
        foreach ($ids->getData() as $item) {
            $list[] = $item['entity_id'];
        }

        return $list;
    }

    /**
     * @return array|mixed
     */
    public function isLayeredEnable()
    {
        return $this->isModuleOutputEnabled('Mageplaza_LayeredNavigation')
            && $this->getConfigValue('layered_navigation/general/enabled');
    }

    /**
     * @param string $id
     * @param string $label
     * @param string $class
     * @param string $style
     *
     * @return string
     */
    public function createButton($id, $label, $class = 'primary', $style = '')
    {
        return '<button type="button" class="' . $class
            . ' action-default custom-button" id="' . $id . '" ' . $style . ' >' . $label . '</button>';
    }
}
