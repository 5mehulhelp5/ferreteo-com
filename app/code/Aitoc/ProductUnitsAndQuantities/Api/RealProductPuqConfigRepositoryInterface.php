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

use Aitoc\ProductUnitsAndQuantities\Api\Data\BaseAdminProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\RealProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Model\RealProductPuqConfig;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * Interface AdminProductPuqConfigRepositoryInterface
 */
interface RealProductPuqConfigRepositoryInterface
{
    /**
     * @param RealProductPuqConfigInterface $adminProductPuqConfig
     * @return BaseAdminProductPuqConfigInterface
     * @throws CouldNotSaveException
     */
    public function save(RealProductPuqConfigInterface $adminProductPuqConfig);

    /**
     * @param int $productId
     * @param int $storeId
     * @return RealProductPuqConfig|null
     */
    public function getByProductIdAndStoreId($productId, $storeId);

    /**
     * @param RealProductPuqConfigInterface $adminProductPuqConfig
     * @return mixed
     */
    public function delete(RealProductPuqConfigInterface $adminProductPuqConfig);
}
