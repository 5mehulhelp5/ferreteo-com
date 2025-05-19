<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Model\Config\Source;

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\Labels\QtyTypeLabelInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\QtyTypeInterface;

/**
 * Class QtyType
 */
class QtyTypeOptionSource extends BaseOptionsAbstractSource
{
    /**
     * @return array
     */
    public function toArray()
    {
        return [
            QtyTypeInterface::TYPE_STATIC => __(QtyTypeLabelInterface::TYPE_STATIC),
            QtyTypeInterface::TYPE_DYNAMIC => __(QtyTypeLabelInterface::TYPE_DYNAMIC)
        ];
    }
}
