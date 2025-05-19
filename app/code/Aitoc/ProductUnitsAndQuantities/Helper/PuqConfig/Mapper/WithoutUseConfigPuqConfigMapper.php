<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Helper\PuqConfig\Mapper;

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfig\PuqConfigBaseSettersInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Helper\PuqConfig\Mapper\WithoutUseConfigMapperInterface;

/**
 * Class WithoutPuqConfigMapper
 */
class WithoutUseConfigPuqConfigMapper implements WithoutUseConfigMapperInterface
{
    public function fillNullValues($from, $to)
    {
        /** QTY */
        if ($to->getReplaceQty() === null) {
            $to->setReplaceQty($from->getReplaceQty());
        }

        if (($to->getIsQtyDecimal()) === null) {
            $to->setIsQtyDecimal($from->getIsQtyDecimal());
        }

        if (($to->getQtyType()) === null) {
            $to->setQtyType($from->getQtyType());
        }

        if (($to->getUseQuantities()) === null) {
            $to->setUseQuantities($from->getUseQuantities());
        }

        if (($to->getUseQuantities()) === null) {
            $to->setUseQuantities($from->getUseQuantities());
        }

        if (($to->getStartQty()) === null) {
            $to->setStartQty($from->getStartQty());
        }

        if (($to->getQtyIncrement()) === null) {
            $to->setQtyIncrement($from->getQtyIncrement());
        }

        if (($to->getEndQty()) === null) {
            $to->setEndQty($from->getEndQty());
        }

        /** UNITS */
        if (($to->getAllowUnits()) === null) {
            $to->setAllowUnits($from->getAllowUnits());
        }

        if (($to->getPricePerDivider()) === null) {
            $to->setPricePerDivider($from->getPricePerDivider());
        }

        if (($to->getPricePer()) === null) {
            $to->setPricePer($from->getPricePer());
        }
    }


    /**
     * @inheritDoc
     */
    public function mapNotNullValues($from, PuqConfigBaseSettersInterface $to)
    {
        /** QTY */
        if (($replaceQty = $from->getReplaceQty()) !== null) {
            $to->setReplaceQty($replaceQty);
        }

        if (($isQtyDecimal = $from->getIsQtyDecimal()) !== null) {
            $to->setIsQtyDecimal($isQtyDecimal);
        }

        if (($qtyType = $from->getQtyType()) !== null) {
            $to->setQtyType($qtyType);
        }

        if (($useQuantities = $from->getUseQuantities()) !== null) {
            $to->setUseQuantities($useQuantities);
        }

        if (($startQty = $from->getStartQty()) !== null) {
            $to->setStartQty($startQty);
        }

        if (($qtyIncrement = $from->getQtyIncrement()) !== null) {
            $to->setQtyIncrement($qtyIncrement);
        }

        if (($endQty = $from->getEndQty()) !== null) {
            $to->setEndQty($endQty);
        }

        /** UNITS */
        if (($allowUnits = $from->getAllowUnits()) !== null) {
            $to->setAllowUnits($allowUnits);
        }

        if (($pricePerDivider = $from->getPricePerDivider()) !== null) {
            $to->setPricePerDivider($pricePerDivider);
        }

        if (($pricePer = $from->getPricePer()) !== null) {
            $to->setPricePer($pricePer);
        }
    }
}
