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
use Aitoc\ProductUnitsAndQuantities\Helper\PuqConfig\Adjuster\ByAspect\Qty\ByIncrementsQtyAdjuster;
use Aitoc\ProductUnitsAndQuantities\Plugin\AbstractBugfixPlugin;
use Magento\Catalog\Block\Product\View;
use Magento\Catalog\Model\Product;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\App\ProductMetadataInterface;

/**
 * Class ViewPlugin
 *
 * Fix to add to qty input[type=number] "min"/"max"/"step" attributes in Magento\Catalog\Block\Product\View
 * @see https://github.com/magento/magento2/pull/14708
 */
class ViewGetMaxSaleQtyPlugin extends AbstractBugfixPlugin
{
    /**
     * @var StockRegistryInterface
     */
    private $stockRegistry;

    /**
     * @var ByIncrementsQtyAdjuster
     */
    private $byIncrementsQtyAdjuster;

    /**
     * @var Product
     */
    private $maximalProduct;

    /**
     * @var Product
     */
    private $minimalProduct;

    /**
     * ViewGetMaxSaleQtyPlugin constructor.
     * @param ProductMetadataInterface $productMetadata
     * @param StockRegistryInterface $stockRegistry
     * @param ByIncrementsQtyAdjuster $byIncrementsQtyAdjuster
     */
    public function __construct(
        ProductMetadataInterface $productMetadata,
        StockRegistryInterface $stockRegistry,
        ByIncrementsQtyAdjuster $byIncrementsQtyAdjuster
    ) {
        parent::__construct($productMetadata);

        $this->stockRegistry = $stockRegistry;
        $this->byIncrementsQtyAdjuster = $byIncrementsQtyAdjuster;
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

        $this->fixValidatorsMinMaxIncrementsValues($block, $validators);

        return $validators;
    }

    /**
     * @return bool
     */
    protected function isBuggyCurrentCoreVersion()
    {
        //todo: validate by version when related pull-request would be merged
        return true;
    }

    /**
     * @param View $block
     * @param array $validators
     * @return array
     */
    private function fixValidatorsMinMaxIncrementsValues(View $block, &$validators)
    {
        $validateItemQty = &$validators[ValidationRulesInterface::VALIDATE_ITEM_QUANTITY];
        $qtyIncrements = (isset($validateItemQty['qtyIncrements'])) ? $validateItemQty['qtyIncrements']: null ;
        $product = $block->getProduct();

        if ($qtyIncrements && isset($validateItemQty['minAllowed'])) {
            $validateItemQty['minAllowed'] = $block->getMinimalQty($product);
        }

        if ($qtyIncrements && isset($validateItemQty['maxAllowed'])) {
            $validateItemQty['maxAllowed']
                = $this->getFixedMaxSaleQty($block, $validateItemQty['maxAllowed'], $product);
        }

        return $validators;
    }

    /**
     * @param View $subject
     * @param float $value
     * @param Product $product
     * @return float
     */
    private function getFixedMaxSaleQty(View $subject, $value, Product $product)
    {
        $qtyIncrements = $this->getQtyIncrements($product);

        return $this->getAdjustedByQtyIncrementsMaxSaleQty($value, $qtyIncrements);
    }

    /**
     * Gets minimal sales quantity
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return int|null
     */
    public function getQtyIncrements($product)
    {
        $stockItem = $this->stockRegistry->getStockItem($product->getId(), $product->getStore()->getWebsiteId());

        return $stockItem->getQtyIncrements();
    }

    /**
     * @param float $rawMaxSaleQty
     * @param float $qtyIncrements
     * @return float
     */
    private function getAdjustedByQtyIncrementsMaxSaleQty($rawMaxSaleQty, $qtyIncrements)
    {
        if (!$qtyIncrements) {
            return $rawMaxSaleQty;
        }

        return $this->byIncrementsQtyAdjuster->getAdjustedMaxValue($rawMaxSaleQty, $qtyIncrements);
    }

    /**
     * @param View $subject
     * @param Product $product
     */
    public function beforeGetMaximalQty(View $subject, Product $product)
    {
        $this->maximalProduct = $product;
    }

    /**
     * @param View $subject $value
     * @param float $value
     * @return float
     */
    public function afterGetMaximalQty(View $subject, $value)
    {
        if (!$this->isBuggyCurrentCoreVersion()) {
            return $value;
        }

        return $this->getFixedMaxSaleQty($subject, $value, $this->maximalProduct);
    }

    /**
     * @param View $subject
     * @param Product $product
     */
    public function beforeGetMinimalQty(View $subject, Product $product)
    {
        $this->minimalProduct = $product;
    }

    /**
     * @param View $subject
     * @param float $value
     * @return float
     */
    public function afterGetMinimalQty(View $subject, $value)
    {
        if (!$this->isBuggyCurrentCoreVersion()) {
            return $value;
        }

        return $this->getFixedMinSaleQty($subject, $value, $this->minimalProduct);
    }

    /**
     * @param View $subject
     * @param float $value
     * @param Product $product
     * @return float
     */
    private function getFixedMinSaleQty(View $subject, $value, $product)
    {
        $qtyIncrements = $this->getQtyIncrements($product);

        return $this->getAdjustedByQtyIncrementsMinSaleQty($value, $qtyIncrements);
    }

    /**
     * @param float $rawMinSaleQty
     * @param float $qtyIncrements
     * @return float
     */
    private function getAdjustedByQtyIncrementsMinSaleQty($rawMinSaleQty, $qtyIncrements)
    {
        if (!$qtyIncrements) {
            return $rawMinSaleQty;
        }

        return $this->byIncrementsQtyAdjuster->getAdjustedMinValue($rawMinSaleQty, $qtyIncrements);
    }
}
