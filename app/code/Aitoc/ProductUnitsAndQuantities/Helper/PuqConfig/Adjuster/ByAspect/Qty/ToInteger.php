<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Helper\PuqConfig\Adjuster\ByAspect\Qty;

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfigWithoutUseConfigInterface;

/**
 * Class ToInteger
 */
class ToInteger
{
    /**
     * @param PuqConfigWithoutUseConfigInterface $puqConfig
     * @return PuqConfigWithoutUseConfigInterface
     */
    public function adjustPuqConfig(PuqConfigWithoutUseConfigInterface $puqConfig)
    {
        //Bundle product couldn't have decimal qty
        $puqConfig->setIsQtyDecimal(false);
        $this->adjustPuqConfigMinQtyToInteger($puqConfig);
        $this->adjustPuqConfigMaxQtyToInteger($puqConfig);
        $this->adjustPuqConfigQtyIncrementToInteger($puqConfig);
        $this->adjustUseQuantities($puqConfig);

        return $puqConfig;
    }

    /**
     * @param PuqConfigWithoutUseConfigInterface $puqConfig
     * @return PuqConfigWithoutUseConfigInterface
     */
    private function adjustPuqConfigMinQtyToInteger(PuqConfigWithoutUseConfigInterface $puqConfig)
    {
        $startQty = $puqConfig->getStartQty();
        $adjustedStartQty = (int) ceil($startQty);
        $puqConfig->setStartQty($adjustedStartQty);

        return $puqConfig;
    }

    /**
     * @param PuqConfigWithoutUseConfigInterface $puqConfig
     * @return PuqConfigWithoutUseConfigInterface
     */
    private function adjustPuqConfigMaxQtyToInteger(PuqConfigWithoutUseConfigInterface $puqConfig)
    {
        $endQty = $puqConfig->getEndQty();
        $adjustedEndQty = (int) floor($endQty);
        $puqConfig->setEndQty($adjustedEndQty);

        return $puqConfig;
    }

    /**
     * @param PuqConfigWithoutUseConfigInterface $puqConfig
     * @return PuqConfigWithoutUseConfigInterface
     */
    private function adjustPuqConfigQtyIncrementToInteger(PuqConfigWithoutUseConfigInterface $puqConfig)
    {
        $qtyIncrement = $puqConfig->getQtyIncrement();
        $adjustedQtyIncrement = (int) round($qtyIncrement);

        if (!$adjustedQtyIncrement) {
            $adjustedQtyIncrement = 1;
        }

        $puqConfig->setQtyIncrement($adjustedQtyIncrement);

        return $puqConfig;
    }

    /**
     * @param PuqConfigWithoutUseConfigInterface $puqConfig
     * @return string
     */
    private function adjustUseQuantities(PuqConfigWithoutUseConfigInterface $puqConfig)
    {
        $useQuantitiesString = $puqConfig->getUseQuantities();
        $useQuantitiesArray = explode(',', $useQuantitiesString);
        $useQuantitiesArray = array_map('floor', $useQuantitiesArray);
        $useQuantitiesArray = array_unique($useQuantitiesArray);
        sort($useQuantitiesArray);

        if ($useQuantitiesArray && $useQuantitiesArray[0] == 0) {
            array_shift($useQuantitiesArray);
        }

        if (!count($useQuantitiesArray)) {
            $useQuantitiesArray[] = 1;
        }

        $adjustedUseQuantitiesString = implode(',', $useQuantitiesArray);

        $puqConfig->setUseQuantities($adjustedUseQuantitiesString);

        return $puqConfig;
    }
}
