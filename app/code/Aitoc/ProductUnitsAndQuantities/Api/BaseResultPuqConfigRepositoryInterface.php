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

/**
 * Interface BaseResultPuqConfigRepositoryInterface
 */
interface BaseResultPuqConfigRepositoryInterface
{
    /**
     * @param int $productId
     * @param int $storeId
     * @return mixed
     */
    public function getByProductIdAndStoreId($productId, $storeId);
}
