<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Plugin\Bundle\Block\Catalog\Product\View\Type\Bundle;

use Aitoc\ProductUnitsAndQuantities\Helper\Data as PuqHelper;
use Magento\Bundle\Block\Catalog\Product\View\Type\Bundle\Option;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class OptionPlugin
 */
class OptionPlugin
{
    /**
     * @var ProductInterface
     */
    private $selection;

    /**
     * @var PuqHelper
     */
    private $unitsHelper;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * OptionPlugin constructor.
     * @param PuqHelper $aitocUnitsHelper
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        PuqHelper $aitocUnitsHelper,
        StoreManagerInterface $storeManager
    ) {
        $this->unitsHelper = $aitocUnitsHelper;
        $this->storeManager = $storeManager;
    }

    /**
     * @param Option $subject
     * @param ProductInterface $selection
     * @return string
     */
    public function beforeRenderPriceString(Option $subject, $selection)
    {
        $this->selection = $selection;

        return null;
    }

    /**
     * @param Option $subject
     * @param string $priceHtml
     * @return string
     * @throws NoSuchEntityException
     */
    public function afterRenderPriceString(Option $subject, $priceHtml)
    {
        $productId = $this->selection->getId();
        $storeId = $this->getCurrentStoreId();

        return $priceHtml . $this->unitsHelper->getPriceAndUnitsHtml($productId, $storeId);
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
