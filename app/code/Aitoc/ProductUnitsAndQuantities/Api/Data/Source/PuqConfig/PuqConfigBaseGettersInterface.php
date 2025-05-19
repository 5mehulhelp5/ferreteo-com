<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfig;

/**
 * Interface PuqConfigGettersInterface
 */
interface PuqConfigBaseGettersInterface
{
    /* Qty */

    /**
     * @return int
     */
    public function getReplaceQty();

    /**
     * @return int
     */
    public function getQtyType();

    /**
     * @return string
     */
    public function getUseQuantities();

    /**
     * @return bool
     */
    public function getIsQtyDecimal();

    /**
     * @return float
     */
    public function getStartQty();

    /**
     * @return float
     */
    public function getQtyIncrement();

    /**
     * @return float
     */
    public function getEndQty();

    /* Units */

    /**
     * @return int
     */
    public function getAllowUnits();

    /**
     * @return string
     */
    public function getPricePer();

    /**
     * @return string
     */
    public function getPricePerDivider();
}
