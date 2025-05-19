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

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\BlockConfigModeInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfigWithoutUseConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\PuqConfig\Adjuster\ByProductTypeId\Bundle
    as ForBundleProductPuqConfigQtyAdjuster;
use Aitoc\ProductUnitsAndQuantities\Helper\PuqConfig\Adjuster\ByProductTypeId\Grouped
    as ForGroupedProductPuqConfigAdjuster;
use Magento\GroupedProduct\Model\Product\Type\Grouped;

/**
 * Class ByProductTypeId
 */
class ByProductTypeId
{
    /**
     * @var ForBundleProductPuqConfigQtyAdjuster
     */
    private $forBundleProductPuqConfigQtyAdjuster;

    /**
     * @var ForGroupedProductPuqConfigAdjuster
     */
    private $forGroupedProductPuqConfigAdjuster;

    /**
     * ByProductTypeId constructor.
     * @param ForBundleProductPuqConfigQtyAdjuster $forBundleProductPuqConfigQtyAdjuster
     * @param ForGroupedProductPuqConfigAdjuster $forGroupedProductPuqConfigAdjuster
     */
    public function __construct(
        ForBundleProductPuqConfigQtyAdjuster $forBundleProductPuqConfigQtyAdjuster,
        ForGroupedProductPuqConfigAdjuster $forGroupedProductPuqConfigAdjuster
    ) {
        $this->forBundleProductPuqConfigQtyAdjuster = $forBundleProductPuqConfigQtyAdjuster;
        $this->forGroupedProductPuqConfigAdjuster = $forGroupedProductPuqConfigAdjuster;
    }

    /**
     * @param string $productTypeId
     * @param PuqConfigWithoutUseConfigInterface $puqConfigWithoutUseConfig
     * @return PuqConfigWithoutUseConfigInterface
     */
    public function adjustPuqConfig($productTypeId, PuqConfigWithoutUseConfigInterface $puqConfigWithoutUseConfig)
    {
        switch ($productTypeId) {
            case BlockConfigModeInterface::BUNDLE:
                return $this->adjustPuqConfigForBundleProduct($puqConfigWithoutUseConfig);
            case Grouped::TYPE_CODE:
                return $this->adjustPuqConfigForGroupedProduct($puqConfigWithoutUseConfig);
            default:
                return $puqConfigWithoutUseConfig;
        }
    }

    /**
     * @param PuqConfigWithoutUseConfigInterface $puqConfigWithoutUseConfig
     * @return PuqConfigWithoutUseConfigInterface
     */
    private function adjustPuqConfigForBundleProduct(PuqConfigWithoutUseConfigInterface $puqConfigWithoutUseConfig)
    {
        return $this->forBundleProductPuqConfigQtyAdjuster->adjustQtyValues($puqConfigWithoutUseConfig);
    }

    /**
     * @param PuqConfigWithoutUseConfigInterface $puqConfigWithoutUseConfig
     * @return PuqConfigWithoutUseConfigInterface
     */
    private function adjustPuqConfigForGroupedProduct(PuqConfigWithoutUseConfigInterface $puqConfigWithoutUseConfig)
    {
        return $this->forGroupedProductPuqConfigAdjuster->adjust($puqConfigWithoutUseConfig);
    }
}
