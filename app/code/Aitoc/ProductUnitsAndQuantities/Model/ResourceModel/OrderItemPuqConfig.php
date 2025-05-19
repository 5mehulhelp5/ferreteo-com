<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Model\ResourceModel;

use Aitoc\ProductUnitsAndQuantities\Api\Data\OrderItemPuqConfigInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class OrderItem
 */
class OrderItemPuqConfig extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('aitoc_product_units_and_quantities_orders', OrderItemPuqConfigInterface::ITEM_ID);
    }
}
