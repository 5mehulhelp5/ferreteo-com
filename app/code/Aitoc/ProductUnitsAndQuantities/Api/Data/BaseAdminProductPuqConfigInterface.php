<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Api\Data;

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfig\PuqConfigUseConfigGettersInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfig\PuqConfigUseConfigSettersInterface;

/**
 * Interface AdminProductPuqConfigInterface
 *
 * Represents puq admin setting for product.
 */
interface BaseAdminProductPuqConfigInterface extends
    ProductPuqConfigInterface,
    PuqConfigUseConfigGettersInterface,
    PuqConfigUseConfigSettersInterface
{

}
