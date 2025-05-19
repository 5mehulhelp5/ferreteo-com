<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Helper;

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfigWithoutUseConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\SystemPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\PuqConfig\Adjuster\ForCurrentProduct
    as ForCurrentProductPuqConfigQtyAdjuster;

/**
 * Class ProductPuqSystemConfiguration
 * @package Aitoc\ProductUnitsAndQuantities\Helper
 */
class ProductPuqSystemConfiguration
{
    /**
     * @var ProductPuqSystemConfiguration
     */
    private $realPuqSystemConfiguration;

    /**
     * @var ForCurrentProductPuqConfigQtyAdjuster
     */
    private $forCurrentProductPuqConfigQtyAdjuster;

    /**
     * ProductPuqSystemConfiguration constructor.
     * @param RealPuqSystemConfiguration $realPuqSystemConfiguration
     * @param ForCurrentProductPuqConfigQtyAdjuster $forCurrentProductPuqConfigQtyAdjuster
     */
    public function __construct(
        RealPuqSystemConfiguration $realPuqSystemConfiguration,
        ForCurrentProductPuqConfigQtyAdjuster $forCurrentProductPuqConfigQtyAdjuster
    ) {
        $this->realPuqSystemConfiguration = $realPuqSystemConfiguration;
        $this->forCurrentProductPuqConfigQtyAdjuster = $forCurrentProductPuqConfigQtyAdjuster;
    }

    /**
     * @inheritdoc
     */
    public function getProductPuqSystemConfig()
    {
        $realPuqSystemConfig = $this->getRealPuqSystemConfig();

        return $this->adjustQtyValuesByCurrentProductTypeId($realPuqSystemConfig);
    }

    /**
     * @return SystemPuqConfigInterface
     */
    private function getRealPuqSystemConfig()
    {
        return $this->realPuqSystemConfiguration->getRealPuqSystemConfig();
    }

    /**
     * @param SystemPuqConfigInterface $realPuqSystemConfig
     * @return PuqConfigWithoutUseConfigInterface
     */
    private function adjustQtyValuesByCurrentProductTypeId(SystemPuqConfigInterface $realPuqSystemConfig)
    {
        return $this->forCurrentProductPuqConfigQtyAdjuster->adjustQtyValues($realPuqSystemConfig);
    }
}
