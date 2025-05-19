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

use Aitoc\ProductUnitsAndQuantities\Api\Data\RealProductPuqConfigInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class AdminProductPuqConfig
 */
class RealProductPuqConfig extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('aitoc_product_units_and_quantities', RealProductPuqConfigInterface::ID);
    }
}
