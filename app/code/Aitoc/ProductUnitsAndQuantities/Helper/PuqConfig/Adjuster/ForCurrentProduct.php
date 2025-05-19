<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Helper\PuqConfig\Adjuster;

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfigWithoutUseConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\PuqConfig\Adjuster\ByProductTypeId as ByProductTypeIdPuqConfigQtyAdjuster;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Locator\LocatorInterface;

/**
 * Class ForCurrentProduct
 */
class ForCurrentProduct
{
    /**
     * @var ByProductTypeIdPuqConfigQtyAdjuster
     */
    private $byProductTypeIdPuqConfigQtyAdjuster;

    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * ForCurrentProduct constructor.
     * @param LocatorInterface $locator
     * @param ByProductTypeIdPuqConfigQtyAdjuster $byProductTypeIdPuqConfigQtyAdjuster
     */
    public function __construct(
        LocatorInterface $locator,
        ByProductTypeIdPuqConfigQtyAdjuster $byProductTypeIdPuqConfigQtyAdjuster
    ) {
        $this->locator = $locator;
        $this->byProductTypeIdPuqConfigQtyAdjuster = $byProductTypeIdPuqConfigQtyAdjuster;
    }

    /**
     * @param PuqConfigWithoutUseConfigInterface $puqConfigWithoutUseConfig
     * @return PuqConfigWithoutUseConfigInterface
     */
    public function adjustQtyValues(PuqConfigWithoutUseConfigInterface $puqConfigWithoutUseConfig)
    {
        $currentProductTypeId = $this->getCurrentProductTypeId();

        return $this->adjustPuqConfigByProductType($puqConfigWithoutUseConfig, $currentProductTypeId);
    }

    /**
     * @return string|null
     */
    private function getCurrentProductTypeId()
    {
        $currentProduct = $this->getCurrentProduct();

        return $currentProduct->getTypeId();
    }

    /**
     * @return ProductInterface
     */
    private function getCurrentProduct()
    {
        return $this->locator->getProduct();
    }

    /**
     * @param PuqConfigWithoutUseConfigInterface $puqConfigWithoutUseConfig
     * @param string $currentProductTypeId
     * @return PuqConfigWithoutUseConfigInterface
     */
    private function adjustPuqConfigByProductType(
        PuqConfigWithoutUseConfigInterface $puqConfigWithoutUseConfig,
        $currentProductTypeId
    ) {
        return $this->byProductTypeIdPuqConfigQtyAdjuster
            ->adjustPuqConfig($currentProductTypeId, $puqConfigWithoutUseConfig);
    }
}
