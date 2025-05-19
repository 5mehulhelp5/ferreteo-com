<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\ViewModel;

use Aitoc\ProductUnitsAndQuantities\Api\ViewModel\PuqConfigInterface;
use Magento\Framework\App\ProductMetadataInterface;

/**
 * Class PuqConfig
 */
class PuqConfig implements PuqConfigInterface
{
    // not implement Magento\Framework\View\Element\Block\ArgumentInterface because its not exists in supported v.2.1.9.

    /**
     * @var ProductMetadataInterface
     */
    private $productMetadata;

    /**
     * PuqConfig constructor.
     * @param ProductMetadataInterface $productMetadata
     */
    public function __construct(ProductMetadataInterface $productMetadata)
    {
        $this->productMetadata = $productMetadata;
    }

    /**
     * @return string
     */
    public function getSerializedConfig()
    {
        $config = $this->getConfig();

        return json_encode($config, true);
    }

    /**
     * @return array
     */
    private function getConfig()
    {
        return [
            'fix_min_max_sale_qty_error_messages' => $this->getFixMinMaxSaleQtyErrorMessage(),
            'fix_validate_grouped_qty' => $this->getFixValidateGroupedQty(),
        ];
    }

    /**
     * @return bool
     * @see https://github.com/magento/magento2/issues/13582#issuecomment-377970718 Minimum quantity validation message not showing
     * Also fixes invalid qty validation by qty increment for float qty values.
     * Todo: make fix in magneto.
     */
    private function getFixMinMaxSaleQtyErrorMessage()
    {
        return true;
    }

    /**
     * @return bool
     */
    private function getFixValidateGroupedQty()
    {
        /**
         * Currently not fixed.
         *
         * @see https://github.com/magento/magento2/issues/14692
         */
        return true;
    }
}
