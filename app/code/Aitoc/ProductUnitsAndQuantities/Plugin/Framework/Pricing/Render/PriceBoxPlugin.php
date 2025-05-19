<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Plugin\Framework\Pricing\Render;

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\AllowUnitsInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\Data as AitocUnitsHelper;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Pricing\Render\PriceBoxRenderInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class PriceBoxPlugin
 */
class PriceBoxPlugin
{
    const PRODUCT_ID_INDEX = 2;

    private $productId;
    private $aitocUnitsHelper;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * PriceBoxPlugin constructor.
     * @param AitocUnitsHelper $aitocUnitsHelper
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        AitocUnitsHelper $aitocUnitsHelper,
        StoreManagerInterface $storeManager
    ) {
        $this->aitocUnitsHelper = $aitocUnitsHelper;
        $this->storeManager = $storeManager;
    }

    /**
     * @param PriceBoxRenderInterface $subject
     * @param float $amount
     * @param array $arguments
     */
    public function beforeRenderAmount(PriceBoxRenderInterface $subject, $amount, $arguments)
    {
        $productId = $this->getProductIdByPriceId($arguments['price_id']);

        if ($productId) {
            $this->productId = $productId;
        }
    }

    /**
     * @param string $priceId
     * @return int
     */
    private function getProductIdByPriceId($priceId)
    {
        $chunks = explode('-', $priceId);

        return isset($chunks[self::PRODUCT_ID_INDEX]) ? (int) $chunks[self::PRODUCT_ID_INDEX] : null;
    }

    /**
     * @param PriceBoxRenderInterface $subject
     * @param string $result
     * @return string
     * @throws NoSuchEntityException
     */
    public function afterRenderAmount(PriceBoxRenderInterface $subject, $result)
    {
        if (!$this->productId) {
            return $result;
        }

        $storeId = $this->getCurrentStoreId();
        $resultProductConfig = $this->aitocUnitsHelper->getResultProductPuqConfigByProductIdAndStockId($this->productId, $storeId);

        if ($resultProductConfig->getAllowUnits() == AllowUnitsInterface::NO) {
            return $result;
        }

        return $result . $this->aitocUnitsHelper->getPriceAndUnitsHtml($this->productId, $storeId);
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
}
