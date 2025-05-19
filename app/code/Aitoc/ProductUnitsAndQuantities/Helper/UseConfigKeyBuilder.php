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

/**
 * Class UseConfigKeyBuilder
 */
class UseConfigKeyBuilder
{
    const USE_CONFIG = 'use_config';

    /**
     * @param string $field
     * @return string
     */
    public function getUseConfigKey($field)
    {
        return self::USE_CONFIG . '_' . $field;
    }
}
