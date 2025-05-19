<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Api\Model\Converter;

use Magento\Framework\Model\AbstractModel;

/**
 * Interface ModelToInterfaceInterface
 */
interface ModelToInterfaceInterface
{
    /**
     * @param AbstractModel $model
     * @return mixed
     */
    public function modelToInterface(AbstractModel $model);
}
