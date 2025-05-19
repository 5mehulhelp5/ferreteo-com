<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Plugin\Framework\Data\Argument\Interpreter;

use Magento\Framework\Data\Argument\Interpreter\Number;

/**
 * Class NumberPlugin
 */
class NumberPlugin
{
    /**
     * @param Number $subject
     * @param string|int|float $result
     * @return float
     */
    public function afterEvaluate(Number $subject, $result)
    {
        return $result + 0;
    }
}
