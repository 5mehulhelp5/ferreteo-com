<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Api\Helper\PuqConfig\Mapper;

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfig\PuqConfigBaseGettersInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfig\PuqConfigBaseSettersInterface;

/**
 * Interface BasePuqConfigMapperInterface
 */
interface BasePuqConfigMapperInterface
{
    /**
     * @param PuqConfigBaseGettersInterface $from
     * @param PuqConfigBaseSettersInterface $to
     * @return void
     */
    public function mapNotNullValues($from, PuqConfigBaseSettersInterface $to);

    public function fillNullValues($from, $to);
}
