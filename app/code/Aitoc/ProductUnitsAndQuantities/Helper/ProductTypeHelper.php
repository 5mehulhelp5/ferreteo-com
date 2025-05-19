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

use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableProductType;
use Magento\GroupedProduct\Model\Product\Type\Grouped as GroupedProductType;

/**
 * Class ProductTypeHelper
 */
class ProductTypeHelper
{
    /**
     * @param string $productTypeId
     * @return bool
     */
    public function isOnOffConfiguredProductTypeId($productTypeId)
    {
        return in_array(
            $productTypeId,
            [GroupedProductType::TYPE_CODE, ConfigurableProductType::TYPE_CODE]
        );
    }
}
