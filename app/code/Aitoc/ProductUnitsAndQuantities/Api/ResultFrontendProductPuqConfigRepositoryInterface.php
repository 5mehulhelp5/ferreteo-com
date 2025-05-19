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

use Aitoc\ProductUnitsAndQuantities\Api\Data\ResultFrontendProductPuqConfigInterface;

/**
 * Interface ResultProductPuqConfigRepositoryInterface
 */
interface ResultFrontendProductPuqConfigRepositoryInterface
{
    /**
     * @param int $productId
     * @param int $storeId
     * @return ResultFrontendProductPuqConfigInterface
     */
    public function getByProductIdAndStoreId($productId, $storeId);
}
