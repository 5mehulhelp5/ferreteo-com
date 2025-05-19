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

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfig\PuqConfigWithoutUseConfigGettersInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfig\PuqConfigWithoutUseConfigSettersInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfig\PuqConfigWithUseConfigGettersInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfig\PuqConfigWithUseConfigSettersInterface;

/**
 * Interface PuqConfigMapperInterface
 */
interface PuqConfigMapperInterface
{
    /**
     * @param PuqConfigWithoutUseConfigGettersInterface $from
     * @param PuqConfigWithoutUseConfigSettersInterface $to
     * @return mixed
     */
    public function mapPuqConfigWithoutUseConfig(
        PuqConfigWithoutUseConfigGettersInterface $from,
        PuqConfigWithoutUseConfigSettersInterface $to
    );

    /**
     * @param PuqConfigWithUseConfigGettersInterface $from
     * @param PuqConfigWithUseConfigSettersInterface $to
     * @return void
     */
    public function mapPuqConfigWithUseConfig(
        PuqConfigWithUseConfigGettersInterface $from,
        PuqConfigWithUseConfigSettersInterface $to
    );
}
