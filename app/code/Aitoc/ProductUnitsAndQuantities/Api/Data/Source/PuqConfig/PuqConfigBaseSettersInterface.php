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
 * Interface PuqConfigSettersInterface
 */
interface PuqConfigBaseSettersInterface
{
    /* Qty */

    /**
     * @param int $inputTypeId
     *
     * @return $this
     */
    public function setReplaceQty($inputTypeId);

    /**
     * @param bool $isQtyDecimal
     *
     * @return $this
     */
    public function setIsQtyDecimal($isQtyDecimal);

    /**
     * @param int $qtyTypeId
     *
     * @return $this
     */
    public function setQtyType($qtyTypeId);

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUseQuantities($value);

    /**
     * @param float $startQty
     *
     * @return $this
     */
    public function setStartQty($startQty);

    /**
     * @param float $qtyIncrement
     *
     * @return $this
     */
    public function setQtyIncrement($qtyIncrement);

    /**
     * @param float $endQty
     *
     * @return $this
     */
    public function setEndQty($endQty);

    /* Units */

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setAllowUnits($value);

    /**
     * @param string $unit
     *
     * @return $this
     */
    public function setPricePer($unit);

    /**
     * @param string $divider
     *
     * @return $this
     */
    public function setPricePerDivider($divider);
}
