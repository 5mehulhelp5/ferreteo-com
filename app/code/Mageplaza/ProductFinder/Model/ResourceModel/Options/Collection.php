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

namespace Mageplaza\ProductFinder\Model\ResourceModel\Options;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Mageplaza\ProductFinder\Model\Options;
use Mageplaza\ProductFinder\Model\ResourceModel\Options as ResourceOptions;

/**
 * Class Collection
 * @package Mageplaza\ProductFinder\Model\ResourceModel\Options
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'option_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'mageplaza_productfinder_filter_options_collection';

    /**
     * @var string
     */
    protected $_eventObject = 'options_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Options::class, ResourceOptions::class);
    }
}
