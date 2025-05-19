<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Api\Data\Source;

/**
 * Interface QtyTypeInterface
 */
interface QtyTypeInterface
{
    //used TYPE-prefix because STATIC as name allowed just from php 7
    const TYPE_STATIC = 0;
    const TYPE_DYNAMIC = 1;
}
