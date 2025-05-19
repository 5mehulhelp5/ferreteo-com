<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Plugin\Checkout\CustomerData;

use Aitoc\ProductUnitsAndQuantities\Helper\Data as PuqHelper;
use Magento\Checkout\CustomerData\ItemPool;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class ItemPoolPlugin
 */
class ItemPoolPlugin
{
    const PUQ_CONFIG_KEY = 'puq_config';

    /**
     * @var PuqHelper
     */
    private $puqHelper;

    /**
     * @var CartItemInterface
     */
    private $quoteItem;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * ItemPoolPlugin constructor.
     * @param PuqHelper $puqHelper
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        PuqHelper $puqHelper,
        StoreManagerInterface $storeManager
    ) {
        $this->puqHelper = $puqHelper;
        $this->storeManager = $storeManager;
    }

    /**
     * @param ItemPool $subject
     * @param CartItemInterface $quoteItem
     * @return null
     */
    public function beforeGetItemData(ItemPool $subject, CartItemInterface $quoteItem)
    {
        $this->quoteItem = $quoteItem;

        return null;
    }

    /**
     * @param ItemPool $subject
     * @param array $itemData
     * @return array
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function afterGetItemData(ItemPool $subject, $itemData)
    {
        $productId =$this->quoteItem->getProductId();

        $storeId = $this->getStoreId();
        $puqConfig = $this->puqHelper->getProductParamsWithMode($productId, $storeId);

        $itemData[self::PUQ_CONFIG_KEY] = $puqConfig;

        return $itemData;
    }

    /**
     * @return int
     * @throws NoSuchEntityException
     */
    private function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }
}
