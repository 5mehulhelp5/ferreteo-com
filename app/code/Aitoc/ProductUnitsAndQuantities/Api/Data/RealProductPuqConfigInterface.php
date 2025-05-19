<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Api\Data;

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfigWithUseConfigInterface;

/**
 * Interface RealAdminProductPuqConfigInterface
 */
interface RealProductPuqConfigInterface extends
    ProductPuqConfigInterface,
    PuqConfigWithUseConfigInterface
{
    /**
     * @return int|null
     */
    public function getId();

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id);

    /**
     * @return int|null
     */
    public function getProductId();

    /**
     * @param int $productId
     *
     * @return $this
     */
    public function setProductId($productId);

    /**
     * @return int
     */
    public function getStoreId();

    /**
     * @param int
     * @return $this
     */
    public function setStoreId($storeId);
}
