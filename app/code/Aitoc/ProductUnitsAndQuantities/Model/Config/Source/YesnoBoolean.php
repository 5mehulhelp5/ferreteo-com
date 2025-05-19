<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class YesnoBoolean
 */
class YesnoBoolean implements ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => true, 'label' => __('Yes')],
            ['value' => false, 'label' => __('No')]
        ];
    }
}
