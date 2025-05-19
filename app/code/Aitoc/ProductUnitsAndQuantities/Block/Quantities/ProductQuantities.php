<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Block\Quantities;

use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class ProductQuantities
 */
class ProductQuantities extends Render
{
    protected $_template = 'Aitoc_ProductUnitsAndQuantities::renderer/product.phtml';

    /**
     * @return string
     * @throws NoSuchEntityException
     * @throws InputException
     */
    public function getJsonEncodedCurrentProductConfig()
    {
        $productParams = $this->getCurrentProductConfigModeProduct();

        return json_encode($productParams);
    }
}
