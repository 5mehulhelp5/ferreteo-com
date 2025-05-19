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
use Aitoc\ProductUnitsAndQuantities\Api\PuqConfigSourceInterface;

/**
 * Class PuqConfigValueSource
 */
class PuqConfigValueSource implements PuqConfigSourceInterface
{
    private $productPuqSystemConfig;

    /**
     * @var ProductPuqSystemConfiguration
     */
    private $productPuqSystemConfiguration;

    /**
     * PuqConfigValueSource constructor.
     * @param \Aitoc\ProductUnitsAndQuantities\Helper\ProductPuqSystemConfiguration $productPuqSystemConfiguration
     */
    public function __construct(ProductPuqSystemConfiguration $productPuqSystemConfiguration)
    {
        $this->productPuqSystemConfiguration = $productPuqSystemConfiguration;
    }

    /**
     * @inheritDoc
     */
    public function getValue($name)
    {
        $productPuqSystemConfig = $this->getCachedProductPuqSystemConfig();
        $productPuqSystemConfigArray = $productPuqSystemConfig->toArray();

        return $productPuqSystemConfigArray[$name];
    }

    /**
     * @return PuqConfigWithoutUseConfigInterface
     */
    private function getCachedProductPuqSystemConfig()
    {
        if (!$this->productPuqSystemConfig) {
            $this->productPuqSystemConfig = $this->getProductPuqSystemConfig();
        }

        return $this->productPuqSystemConfig;
    }

    /**
     * @return PuqConfigWithoutUseConfigInterface
     */
    private function getProductPuqSystemConfig()
    {
        return $this->productPuqSystemConfiguration->getProductPuqSystemConfig();
    }
}
