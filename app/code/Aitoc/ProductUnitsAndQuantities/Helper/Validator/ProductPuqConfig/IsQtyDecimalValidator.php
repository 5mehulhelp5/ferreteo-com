<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Helper\Validator\ProductPuqConfig;

use Aitoc\ProductUnitsAndQuantities\Helper\Validator\ProductPuqConfig\Base\BaseInArrayValidator;

/**
 * Class IsQtyDecimalValidator
 */
class IsQtyDecimalValidator extends BaseInArrayValidator
{
    /**
     * @inheritDoc
     */
    protected function getErrorMessage()
    {
        return 'Invalid "Is Qty Decimal" value';
    }

    /**
     * @inheritDoc
     */
    protected function getPossibleValues()
    {
        return [true, false];
    }
}
