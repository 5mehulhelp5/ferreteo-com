<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Model\Data;

use Aitoc\ProductUnitsAndQuantities\Api\Data\ResultAdminProductPuqConfigInterface;

/**
 * Class ResultAdminProductPuqConfig
 */
class ResultAdminProductPuqConfig extends BaseAdminProductPuqConfig implements
    ResultAdminProductPuqConfigInterface
{
    /**
     * @return array
     */
    public function toArray()
    {
        $parentFields = parent::toArray();
        $useConfigFields = $this->getUseConfigFieldsAsArray();

        return $parentFields + $useConfigFields;
    }

    /**
     * @return array
     */
    private function getUseConfigFieldsAsArray()
    {
        return [
            $this->getUseConfigFieldName(static::REPLACE_QTY) => $this->getUseConfigReplaceQty(),
            $this->getUseConfigFieldName(static::IS_QTY_DECIMAL) => $this->getUseConfigIsQtyDecimal(),
            $this->getUseConfigFieldName(static::QTY_TYPE) => $this->getUseConfigQtyType(),
            $this->getUseConfigFieldName(static::USE_QUANTITIES) => $this->getUseConfigUseQuantities(),
            $this->getUseConfigFieldName(static::START_QTY) => $this->getUseConfigStartQty(),
            $this->getUseConfigFieldName(static::QTY_INCREMENT) => $this->getUseConfigQtyIncrement(),
            $this->getUseConfigFieldName(static::END_QTY) => $this->getUseConfigEndQty(),
            $this->getUseConfigFieldName(static::ALLOW_UNITS) => $this->getUseConfigAllowUnits(),
            $this->getUseConfigFieldName(static::PRICE_PER) => $this->getUseConfigPricePer(),
            $this->getUseConfigFieldName(static::PRICE_PER_DIVIDER) => $this->getUseConfigPricePerDivider(),
        ];
    }
}
