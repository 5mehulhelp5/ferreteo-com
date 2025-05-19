<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Helper\PuqConfig\Adjuster\ByProductTypeId;

use Aitoc\ProductUnitsAndQuantities\Helper\PuqConfig\Adjuster\ByAspect\ReplaceQty\ToOnOff as ReplaceQtyToOnOffAdjuster;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfigWithoutUseConfigInterface;

/**
 * Class Grouped
 */
class Grouped
{
    /**
     * @var ReplaceQtyToOnOffAdjuster
     */
    private $replaceQtyToOnOffAdjuster;

    /**
     * Grouped constructor.
     * @param ReplaceQtyToOnOffAdjuster $replaceQtyToOnOffAdjuster
     */
    public function __construct(ReplaceQtyToOnOffAdjuster $replaceQtyToOnOffAdjuster)
    {
        $this->replaceQtyToOnOffAdjuster = $replaceQtyToOnOffAdjuster;
    }

    /**
     * @param PuqConfigWithoutUseConfigInterface $puqConfig
     * @return PuqConfigWithoutUseConfigInterface
     */
    public function adjust(PuqConfigWithoutUseConfigInterface $puqConfig)
    {
        return $this->adjustReplaceQty($puqConfig);
    }

    /**
     * @param PuqConfigWithoutUseConfigInterface $puqConfig
     * @return PuqConfigWithoutUseConfigInterface
     */
    private function adjustReplaceQty(PuqConfigWithoutUseConfigInterface $puqConfig)
    {
        $replaceQty = $puqConfig->getReplaceQty();
        $adjustedReplaceQty = $this->getAdjustedReplaceQty($replaceQty);
        $puqConfig->setReplaceQty($adjustedReplaceQty);

        return $puqConfig;
    }

    /**
     * @param int $replaceQty
     * @return int
     */
    private function getAdjustedReplaceQty($replaceQty)
    {
        return $this->replaceQtyToOnOffAdjuster->convert($replaceQty);
    }
}
