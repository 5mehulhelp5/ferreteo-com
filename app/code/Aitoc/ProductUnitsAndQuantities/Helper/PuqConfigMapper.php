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

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfig\PuqConfigWithoutUseConfigGettersInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfig\PuqConfigWithoutUseConfigSettersInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfig\PuqConfigWithUseConfigGettersInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfig\PuqConfigWithUseConfigSettersInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Helper\PuqConfig\Mapper\PuqConfigMapperInterface;

/**
 * Class PuqConfigMapper
 */
class PuqConfigMapper implements PuqConfigMapperInterface
{
    /**
     * @inheritdoc
     */
    public function mapPuqConfigWithoutUseConfig(
        PuqConfigWithoutUseConfigGettersInterface $from,
        PuqConfigWithoutUseConfigSettersInterface $to
    ) {
        $to
            ->setReplaceQty($from->getReplaceQty())
            ->setIsQtyDecimal($from->getIsQtyDecimal())
            ->setQtyType($from->getQtyType())
            ->setStartQty($from->getStartQty())
            ->setQtyIncrement($from->getQtyIncrement())
            ->setEndQty($from->getEndQty())
            ->setAllowUnits($from->getAllowUnits())
            ->setPricePer($from->getPricePer())
            ->setPricePerDivider($from->getPricePerDivider())
            ->setUseQuantities($from->getUseQuantities());
    }

    /**
     * @inheritDoc
     */
    public function mapPuqConfigWithUseConfig(
        PuqConfigWithUseConfigGettersInterface $from,
        PuqConfigWithUseConfigSettersInterface $to
    ) {
        $to
            ->setUseConfigReplaceQty($from->getUseConfigReplaceQty())
            ->setUseConfigIsQtyDecimal($from->getUseConfigIsQtyDecimal())
            ->setUseConfigQtyType($from->getUseConfigQtyType())
            ->setUseConfigUseQuantities($from->getUseConfigUseQuantities())
            ->setUseConfigStartQty($from->getUseConfigStartQty())
            ->setUseConfigQtyIncrement($from->getUseConfigQtyIncrement())
            ->setUseConfigEndQty($from->getUseConfigEndQty())
            ->setUseConfigAllowUnits($from->getUseConfigAllowUnits())
            ->setUseConfigPricePer($from->getUseConfigPricePer())
            ->setUseConfigPricePerDivider($from->getUseConfigPricePerDivider());
    }
}
