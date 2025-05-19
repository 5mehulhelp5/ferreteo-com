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

use Aitoc\ProductUnitsAndQuantities\Helper\ReplaceQtyOptionsHelper;

/**
 * Class ReplaceQty
 */
class ReplaceQtyOptionSourceWithOn extends BaseOptionsAbstractSource
{
    private $replaceQtyOptionsHelper;

    /**
     * ReplaceQty constructor.
     *
     * @param ReplaceQtyOptionsHelper $replaceQtyOptionsHelper
     */
    public function __construct(ReplaceQtyOptionsHelper $replaceQtyOptionsHelper)
    {
        $this->replaceQtyOptionsHelper = $replaceQtyOptionsHelper;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return $this->replaceQtyOptionsHelper->getValues();
    }
}
