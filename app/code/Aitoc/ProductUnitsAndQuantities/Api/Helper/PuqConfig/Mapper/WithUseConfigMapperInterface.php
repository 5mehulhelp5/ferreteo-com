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

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfig\PuqConfigBaseSettersInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\RealProductPuqConfigInterface;

/**
 * Interface WithUseConfigMapperInterface
 */
interface WithUseConfigMapperInterface extends WithoutUseConfigMapperInterface
{
    /**
     * @param $from
     * @param PuqConfigBaseSettersInterface $to
     */
    public function mapNotNullUseConfigValues(
        $from,
        PuqConfigBaseSettersInterface $to
    );
}
