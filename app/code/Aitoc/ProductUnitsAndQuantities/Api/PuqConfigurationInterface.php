<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Api;

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfig\PuqConfigWithoutUseConfigGettersInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfigFieldIdsInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\SystemPuqConfigInterface;

/**
 * Interface PuqSystemConfigHelper
 */
interface PuqConfigurationInterface extends
    PuqConfigWithoutUseConfigGettersInterface,
    PuqConfigFieldIdsInterface
{
    /**
     * @return SystemPuqConfigInterface
     */
    public function getRealPuqSystemConfig();
}
