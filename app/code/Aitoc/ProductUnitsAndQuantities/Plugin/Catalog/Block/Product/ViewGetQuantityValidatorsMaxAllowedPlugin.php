<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Plugin\Catalog\Block\Product;

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\ValidationRulesInterface;
use Magento\Catalog\Block\Product\View;
use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\App\ProductMetadataInterface;

/**
 * Class ViewPlugin
 *
 * Fix GetQuantityValidators() (['validate-item-quantity']['maxAllowed']) in Magento\Catalog\Block\Product\View
 * @see https://github.com/magento/magento2/issues/13582
 */
class ViewGetQuantityValidatorsMaxAllowedPlugin
{
    const MIN_FIXED_VERSION = '2.3.0';

    /**
     * @var ProductMetadataInterface
     */
    private $productMetadata;

    /**
     * @var StockRegistryInterface
     */
    private $stockRegistry;

    /**
     * ViewGetQuantityValidatorsMaxAllowedPlugin constructor.
     * @param ProductMetadataInterface $productMetadata
     * @param StockRegistryInterface $stockRegistry
     */
    public function __construct(ProductMetadataInterface $productMetadata, StockRegistryInterface $stockRegistry)
    {
        $this->productMetadata = $productMetadata;
        $this->stockRegistry = $stockRegistry;
    }

    /**
     * @param View $block
     * @param array $validators
     * @return array
     */
    public function afterGetQuantityValidators(View $block, array $validators)
    {
        if (!$this->isBuggyCurrentCoreVersion()) {
            return $validators;
        }

        $this->fixMaxAllowed($block, $validators);

        return $validators;
    }

    /**
     * @return bool
     */
    private function isBuggyCurrentCoreVersion()
    {
        $currentVersion = $this->getCurrentCoreVersion();

        return version_compare($currentVersion, self::MIN_FIXED_VERSION, '<');
    }

    /**
     * @return string
     */
    private function getCurrentCoreVersion()
    {
        return $this->productMetadata->getVersion();
    }

    /**
     * @param View $block
     * @param array $validators
     */
    private function fixMaxAllowed(View $block, &$validators)
    {
        $stockItem = $this->getStockItem($block);

        if ($maxSaleQty = $stockItem->getMaxSaleQty()) {
            $validators[ValidationRulesInterface::VALIDATE_ITEM_QUANTITY]['maxAllowed'] = (float) $maxSaleQty;
        }
    }

    /**
     * @param View $block
     * @return StockItemInterface
     */
    private function getStockItem($block)
    {
        return $this->stockRegistry->getStockItem(
            $block->getProduct()->getId(),
            $block->getProduct()->getStore()->getWebsiteId()
        );
    }
}
