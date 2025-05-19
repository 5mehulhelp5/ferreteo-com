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

use Aitoc\ProductUnitsAndQuantities\Block\Quantities\CartQuantities;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Block\Cart\Item\Renderer;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Model\Quote\Item\AbstractItem;

/**
 * Class RenderCartPlugin
 */
class RenderCartPlugin
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var AbstractItem
     */
    //todo: add possible interfaces?
    private $item;

    /**
     * RenderCartPlugin constructor.
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param Renderer $subject
     * @param AbstractItem $item
     */
    public function beforeGetActions(Renderer $subject, AbstractItem $item)
    {
        $this->item = $item;
    }

    /**
     * @param Renderer $subject
     * @param string $result
     * @return string
     * @throws LocalizedException
     */
    public function afterGetActions(Renderer $subject, $result)
    {
        $item = $this->item;

        $itemId = $item->getItemId();
        $productId = $item->getProductId();

        /** @var CartQuantities $block */
        $block = $subject->getLayout()->createBlock(CartQuantities::class);
        $block
            ->setProductId($productId)
            ->setItemId($itemId);

        $result .= $block->toHtml();

        return $result;
    }
}
