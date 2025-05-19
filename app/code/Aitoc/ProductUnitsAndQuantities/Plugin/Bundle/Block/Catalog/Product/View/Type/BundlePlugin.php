<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Plugin\Bundle\Block\Catalog\Product\View\Type;

use Aitoc\ProductUnitsAndQuantities\Helper\Data as UnitsAndQuantitiesHelper;
use Magento\Bundle\Block\Catalog\Product\View\Type\Bundle;
use Magento\Bundle\Model\Product\Type as BundleProductType;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Json\EncoderInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class BundlePlugin
 *
 * Add 'units' to $config['optionConfig']['options'][{optionId}]['selections'][{selectionId}]['prices']['finalPrice']
 */
class BundlePlugin
{
    /**
     * @var EncoderInterface
     */
    private $encoder;

    /**
     * @var UnitsAndQuantitiesHelper
     */
    private $unitsAndQuantitiesHelper;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * BundlePlugin constructor.
     * @param EncoderInterface $encoder
     * @param UnitsAndQuantitiesHelper $unitsAndQuantitiesHelper
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        EncoderInterface $encoder,
        UnitsAndQuantitiesHelper $unitsAndQuantitiesHelper,
        StoreManagerInterface $storeManager
    )
    {
        $this->encoder = $encoder;
        $this->unitsAndQuantitiesHelper = $unitsAndQuantitiesHelper;
        $this->storeManager = $storeManager;
    }

    /**
     * @param Bundle $subject
     * @param string $result
     * @return string
     * @throws NoSuchEntityException
     */
    public function afterGetJsonConfig(Bundle $subject, $result)
    {
        $product = $subject->getProduct();
        $storeId = $this->getCurrentStoreId();
        $decodedResult = json_decode($result, true);
        $this->addPriceUnitsToOptions($decodedResult, $product, $storeId);

        return $this->encoder->encode($decodedResult);
    }

    /**
     * @return int
     * @throws NoSuchEntityException
     */
    private function getCurrentStoreId()
    {
        return $this->getCurrentStore()->getId();
    }

    /**
     * @return StoreInterface
     * @throws NoSuchEntityException
     */
    private function getCurrentStore()
    {
        return $this->storeManager->getStore();
    }


    /**
     * @param array $decodedResult
     * @param Product $product
     * @param int $storeId
     */
    private function addPriceUnitsToOptions(&$decodedResult, $product, $storeId)
    {
        $options = $decodedResult['options'];
        $optionsWithPriceUnits = $this->getOptionsWithPriceUnits($options, $product, $storeId);
        $decodedResult['options'] = $optionsWithPriceUnits;
    }

    /**
     * @param array $options
     * @param Product $product
     * @param int $storeId
     * @return array
     */
    private function getOptionsWithPriceUnits($options, $product, $storeId)
    {
        $ret = [];

        foreach ($options as $optionId => $option) {
            $optionWithPriceUnits = $this->getOptionWithPriceUnits($option, $product, $storeId);
            $ret[$optionId] = $optionWithPriceUnits;
        }

        return $ret;
    }

    /**
     * @param array $option
     * @param Product $product
     * @param int $storeId
     * @return array
     */
    private function getOptionWithPriceUnits($option, $product, $storeId)
    {
        $option['selections'] = $this->getSelectionsWithPriceUnits($option['selections'], $product, $storeId);

        return $option;
    }

    /**
     * @param array $selections
     * @param Product $product
     * @param int $storeId
     * @return array
     */
    private function getSelectionsWithPriceUnits($selections, $product, $storeId)
    {
        $ret = [];

        foreach ($selections as $selectionId => $selection) {
            $ret[$selectionId] = $this->getSelectionWithPriceUnits($selection, $product, $storeId, $selectionId);
        }

        return $ret;
    }

    /**
     * @param array $selection
     * @param Product $product
     * @param int $storeId
     * @param int $selectionId
     * @return array
     */
    private function getSelectionWithPriceUnits($selection, $product, $storeId, $selectionId)
    {
        $selection['prices'] = $this->getPricesWithPriceUnits($selection['prices'], $product, $storeId, $selectionId);

        return $selection;
    }

    /**
     * @param array $prices
     * @param Product $product
     * @param int $storeId
     * @param int $selectionId
     * @return array
     */
    private function getPricesWithPriceUnits($prices, $product, $storeId, $selectionId)
    {
        $prices['finalPrice'] = $this->getPriceWithUnits($prices['finalPrice'], $product, $storeId,$selectionId);

        return $prices;
    }

    /**
     * @param array $finalPrice
     * @param Product $product
     * @param int $storeId
     * @param int $selectionId
     * @return array
     */
    private function getPriceWithUnits($finalPrice, $product, $storeId, $selectionId)
    {
        $finalPrice['units'] = $this->getPriceUnits($product, $storeId, $selectionId);

        return $finalPrice;
    }

    /**
     * @param Product $product
     * @param int $storeId
     * @param int $selectionId
     * @return string
     */
    private function getPriceUnits(Product $product, $storeId, $selectionId)
    {
        /** @var BundleProductType $productTypeInstance */
        $productTypeInstance = $product->getTypeInstance();
        $selections =  $productTypeInstance->getSelectionsByIds([$selectionId], $product);

        /** @var Product $selection */
        $selection = $selections->getItemById($selectionId);

        $productId = $selection->getId();

        return $this->getPriceAndUnitsText($productId, $storeId);
    }

    private function getPriceAndUnitsText($productId, $storeId)
    {
        return $this->unitsAndQuantitiesHelper->getPriceAndUnitsText($productId, $storeId);
    }
}
