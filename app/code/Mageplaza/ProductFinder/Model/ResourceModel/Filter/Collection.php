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

namespace Mageplaza\ProductFinder\Model\ResourceModel\Filter;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Mageplaza\ProductFinder\Model\Filter;
use Mageplaza\ProductFinder\Model\ResourceModel\Filter as ResourceFilter;

/**
 * Class Collection
 * @package Mageplaza\ProductFinder\Model\ResourceModel\Filter
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'filter_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'mageplaza_productfinder_filter_collection';

    /**
     * @var string
     */
    protected $_eventObject = 'filter_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Filter::class, ResourceFilter::class);
    }
}
