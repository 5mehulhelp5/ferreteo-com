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

namespace Mageplaza\ProductFinder\Model\Layer;

use Magento\Catalog\Model\Layer\Category\FilterableAttributeList;
use Magento\Catalog\Model\Layer\FilterList as CoreFilterList;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Framework\ObjectManagerInterface;
use Mageplaza\LayeredNavigation\Model\Layer\Filter\Price;
use Mageplaza\ProductFinder\Helper\Data;
use Magento\Catalog\Model\Config\LayerCategoryConfig;

/**
 * Class FilterList
 * @package Mageplaza\ProductFinder\Model\Layer
 */
class FilterList extends CoreFilterList
{
    /**
     * @var Data
     */
    protected $helperData;

    /**
     * FilterList constructor.
     *
     * @param ObjectManagerInterface $objectManager
     * @param FilterableAttributeList $filterableAttributes
     * @param Data $helperData
     * @param array $filters
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        FilterableAttributeList $filterableAttributes,
        LayerCategoryConfig $layerCategoryConfig,
        Data $helperData,
        array $filters = []
    ) {
        $this->helperData = $helperData;
        parent::__construct($objectManager, $filterableAttributes, $layerCategoryConfig, $filters);
    }

    /**
     * @param Attribute $attribute
     *
     * @return mixed|string
     */
    protected function getAttributeFilterClass(Attribute $attribute)
    {
        $filterClassName = $this->filterTypes[self::ATTRIBUTE_FILTER];

        if ($attribute->getAttributeCode() === 'price') {
            if ($this->helperData->isLayeredEnable()) {
                $this->filterTypes[self::PRICE_FILTER] = Price::class;
            }
            $filterClassName = $this->filterTypes[self::PRICE_FILTER];
        } elseif ($attribute->getBackendType() === 'decimal') {
            $filterClassName = $this->filterTypes[self::DECIMAL_FILTER];
        }

        return $filterClassName;
    }
}
