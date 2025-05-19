<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Plugin\Block;

use Aitoc\ProductUnitsAndQuantities\Block\Quantities\WishlistQuantities;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\BlockInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Wishlist\Block\Customer\Wishlist\Item\Column\Cart;

/**
 * Class RenderWishListPlugin
 */
class RenderWishListPlugin
{
    /** @var StoreManagerInterface */
    private $storeManager;

    /**
     * RenderWishListPlugin constructor.
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(StoreManagerInterface $storeManager)
    {
        $this->storeManager = $storeManager;
    }

    /**
     * @param Cart $subject
     * @param string $result
     * @return string
     * @throws LocalizedException
     */
    public function afterToHtml(Cart $subject, $result)
    {
        $wishListQuantitiesHtml = $this->getWishListQuantitiesHtml($subject);
        $result .= $wishListQuantitiesHtml;

        return $result;
    }

    /**
     * @param Cart $subject
     * @return string
     * @throws LocalizedException
     */
    private function getWishListQuantitiesHtml(Cart $subject)
    {
        $productId = $this->getProductIdByCart($subject);

        /** @var WishlistQuantities $wishListQuantitiesBlock */
        $wishListQuantitiesBlock = $this->createWishListQuantitiesBlock($subject);
        $storeId = $this->getStoreId();

        $wishListQuantitiesBlock->setProductId($productId);

        return $wishListQuantitiesBlock->toHtml();
    }

    /**
     * @param Cart $cart
     * @return int
     */
    private function getProductIdByCart(Cart $cart)
    {
        return $cart->getItem()->getProduct()->getId();
    }

    /**
     * @param Cart $subject
     * @return BlockInterface
     * @throws LocalizedException
     */
    private function createWishListQuantitiesBlock(Cart $subject)
    {
        return $subject->getLayout()->createBlock(WishlistQuantities::class);
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
