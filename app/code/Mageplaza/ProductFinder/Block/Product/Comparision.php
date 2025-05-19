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

namespace Mageplaza\ProductFinder\Block\Product;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Mageplaza\ProductFinder\Helper\Data;
use Mageplaza\ProductFinder\Model\ResourceModel\Filter as ResourceFilter;
use Mageplaza\ProductFinder\Model\ResourceModel\Options as ResourceOption;
use Mageplaza\ProductFinder\Model\ResourceModel\Product as ResourceProduct;
use Mageplaza\ProductFinder\Model\ResourceModel\Product\CollectionFactory as FilterProductFactory;

/**
 * Class Comparision
 * @package Mageplaza\ProductFinder\Block\Product
 */
class Comparision extends Template
{
    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var FilterProductFactory
     */
    protected $filterProductFactory;

    /**
     * @var ResourceFilter
     */
    protected $resourceFilter;

    /**
     * @var ResourceProduct
     */
    protected $resourceProduct;

    /**
     * @var ResourceOption
     */
    protected $resourceOption;

    /**
     * Comparision constructor.
     *
     * @param Context $context
     * @param Registry $coreRegistry
     * @param FilterProductFactory $filterProductFactory
     * @param ResourceFilter $resourceFilter
     * @param ResourceProduct $resourceProduct
     * @param ResourceOption $resourceOption
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        FilterProductFactory $filterProductFactory,
        ResourceFilter $resourceFilter,
        ResourceProduct $resourceProduct,
        ResourceOption $resourceOption
    ) {
        $this->coreRegistry         = $coreRegistry;
        $this->filterProductFactory = $filterProductFactory;
        $this->resourceFilter       = $resourceFilter;
        $this->resourceProduct      = $resourceProduct;
        $this->resourceOption       = $resourceOption;
        parent::__construct($context);

        $this->setTabTitle();
    }

    /**
     * @return mixed
     */
    public function setTabTitle()
    {
        $title = __('Comparision');
        $this->setTitle($title);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrentProduct()
    {
        return $this->coreRegistry->registry('current_product');
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    public function getCompareCollection()
    {
        $collection = $this->filterProductFactory->create();
        $collection->addFieldToFilter('product_sku', $this->getCurrentProduct()->getSku());
        $collection->setOrder('filter_ids', 'ASC')->setPageSize(10)->load();
        $list            = [];
        $data            = $collection->getData();
        $resourceProduct = $this->resourceProduct;
        $resourceFilter  = $this->resourceFilter;

        foreach ($data as $value) {
            $filterIds = Data::jsonDecode($value['filter_ids']);
            $optionIds = Data::jsonDecode($value['filter_options']);
            if (is_array($filterIds)) {
                foreach ($filterIds as $keyFilter => $filterId) {
                    $list[$resourceFilter->getLabelByFilterId($filterId)][]
                        = $this->resourceOption->getLabelByOptionId($optionIds[$keyFilter]);
                }
            } else {
                $list[$resourceFilter->getLabelByFilterId($filterIds)][] = $resourceProduct->getLabelAutoRule(
                    $value['filter_options'],
                    $resourceProduct->getTable('eav_attribute_option_value')
                );
            }
        }

        return $list;
    }
}
