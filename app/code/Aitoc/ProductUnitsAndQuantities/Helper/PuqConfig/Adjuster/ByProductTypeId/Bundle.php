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

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfigWithoutUseConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\PuqConfig\Adjuster\ByAspect\Qty\ToInteger as ToIntegerPuqConfigQtyAdjuster;

/**
 * Class ForBundleProduct
 */
class Bundle
{
    private $toIntegerPuqConfigQtyAdjuster;

    /**
     * ForBundleProduct constructor.
     * @param ToIntegerPuqConfigQtyAdjuster $toIntegerPuqConfigQtyAdjuster
     */
    public function __construct(ToIntegerPuqConfigQtyAdjuster $toIntegerPuqConfigQtyAdjuster)
    {
        $this->toIntegerPuqConfigQtyAdjuster = $toIntegerPuqConfigQtyAdjuster;
    }

    /**
     * @param PuqConfigWithoutUseConfigInterface $puqConfigWithoutUseConfig
     * @return PuqConfigWithoutUseConfigInterface
     */
    public function adjustQtyValues(PuqConfigWithoutUseConfigInterface $puqConfigWithoutUseConfig)
    {
        return $this->toIntegerPuqConfigQtyAdjuster->adjustPuqConfig($puqConfigWithoutUseConfig);
    }
}
