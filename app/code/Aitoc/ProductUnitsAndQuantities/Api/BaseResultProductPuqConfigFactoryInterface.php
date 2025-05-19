<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Api;

use Aitoc\ProductUnitsAndQuantities\Api\Data\BaseResultProductPuqConfigInterface;

/**
 * Interface BaseResultProductPuqConfigFactoryInterface
 */
interface BaseResultProductPuqConfigFactoryInterface
{
    /**
     * @return BaseResultProductPuqConfigInterface
     */
    public function create();
}
