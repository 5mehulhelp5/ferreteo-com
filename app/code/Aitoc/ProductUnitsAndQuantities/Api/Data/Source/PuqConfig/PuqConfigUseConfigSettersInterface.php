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
interface PuqConfigUseConfigSettersInterface
{
    /**
     * @param bool $inputTypeId
     *
     * @return self
     */
    public function setUseConfigReplaceQty($inputTypeId);

    /**
     * @param bool $isQtyDecimal
     * @return self
     */
    public function setUseConfigIsQtyDecimal($isQtyDecimal);

    /**
     * @param bool $qtyTypeId
     *
     * @return self
     */
    public function setUseConfigQtyType($qtyTypeId);

    /**
     * @param bool $value
     *
     * @return self
     */
    public function setUseConfigUseQuantities($value);

    /**
     * @param bool $value
     *
     * @return self
     */
    public function setUseConfigStartQty($value);

    /**
     * @param bool $value
     *
     * @return self
     */
    public function setUseConfigQtyIncrement($value);

    /**
     * @param bool $value
     *
     * @return self
     */
    public function setUseConfigEndQty($value);

    /**
     * @param bool $value
     *
     * @return self
     */
    public function setUseConfigAllowUnits($value);

    /**
     * @param bool $value
     *
     * @return self
     */
    public function setUseConfigPricePer($value);

    /**
     * @param bool $value
     *
     * @return self
     */
    public function setUseConfigPricePerDivider($value);
}
