<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Model\ResourceModel\RealProductPuqConfig;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Aitoc\ProductUnitsAndQuantities\Model\RealProductPuqConfig;
use Aitoc\ProductUnitsAndQuantities\Model\ResourceModel\RealProductPuqConfig as RealProductPuqConfigResource;

/**
 * Class Collection
 */
class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            RealProductPuqConfig::class,
            RealProductPuqConfigResource::class
        );
    }
}
