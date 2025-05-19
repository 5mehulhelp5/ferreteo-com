<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Model\ResourceModel\OrderItem;

use Aitoc\ProductUnitsAndQuantities\Api\Data\RealProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Model\OrderItemPuqConfig as OrderItemModel;
use Aitoc\ProductUnitsAndQuantities\Model\ResourceModel\OrderItemPuqConfig as OrderResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = RealProductPuqConfigInterface::ID;
    protected $_eventPrefix = 'aitoc_productunitsandquantities_order_collection';
    protected $_eventObject = 'order_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            OrderItemModel::class,
            OrderResourceModel::class
        );
    }
}
