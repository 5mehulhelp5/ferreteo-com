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
 * Interface PuqConfigUseConfigGettersInterface
 */
interface PuqConfigUseConfigGettersInterface
{
    /* Qty */

    /**
     * @return bool
     */
    public function getUseConfigReplaceQty();

    /**
     * @return bool
     */
    public function getUseConfigQtyType();

    /**
     * @return bool
     */
    public function getUseConfigIsQtyDecimal();

    /**
     * @return bool
     */
    public function getUseConfigUseQuantities();

    /**
     * @return bool
     */
    public function getUseConfigStartQty();

    /**
     * @return bool
     */
    public function getUseConfigQtyIncrement();

    /**
     * @return bool
     */
    public function getUseConfigEndQty();

    /* Units */

    /**
     * @return bool
     */
    public function getUseConfigAllowUnits();

    /**
     * @return bool
     */
    public function getUseConfigPricePer();

    /**
     * @return bool
     */
    public function getUseConfigPricePerDivider();
}
