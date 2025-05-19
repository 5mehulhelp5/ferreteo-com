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

use Aitoc\ProductUnitsAndQuantities\Api\Data\RealProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\Data as AitocUnitsHelper;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote\Item\AbstractItem as QuoteItem;
use Magento\Sales\Model\Order\CreditMemo\Item as CreditMemoItem;
use Magento\Sales\Model\Order\Invoice\Item as InvoiceItem;
use Magento\Sales\Model\Order\Item as OrderItem;
use Magento\Weee\Block\Item\Price\Renderer;

/**
 * Class RenderOrderItemPricePlugin
 */
class RenderOrderItemPricePlugin
{
    const CART_SUBTOTAL_RPICE_BLOCK_NAME = 'checkout.item.price.row';

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var AitocUnitsHelper
     */
    private $aitocUnitsHelper;

    /**
     * RenderOrderItemPricePlugin constructor.
     *
     * @param ProductRepositoryInterface $productRepository
     * @param AitocUnitsHelper $aitocUnitsHelper
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        AitocUnitsHelper $aitocUnitsHelper
    ) {
        $this->productRepository = $productRepository;
        $this->aitocUnitsHelper = $aitocUnitsHelper;
    }

    /**
     * @param Renderer $subject
     * @param string $result
     * @return string
     * @throws NoSuchEntityException
     */
    public function afterFormatPrice(Renderer $subject, $result)
    {
        if ($subject->getNameInLayout() == self::CART_SUBTOTAL_RPICE_BLOCK_NAME) {
            return  $result;
        }

        $result .= $this->getPriceAndUnitsHtmlByRenderer($subject);

        return $result;
    }

    /**
     * @param Renderer $subject
     * @return string
     * @throws NoSuchEntityException
     */
    private function getPriceAndUnitsHtmlByRenderer(Renderer $subject)
    {
        $product = $this->getProductByRenderer($subject);
        $storeId = $subject->getStoreId();

        return $this->getPriceAndUnitsHtmlByProductAndStoreId($product, $storeId);
    }

    /**
     * @param Renderer $subject
     * @return ProductInterface
     * @throws NoSuchEntityException
     */
    private function getProductByRenderer(Renderer $subject)
    {
        $orderItem = $subject->getItem();

        return $this->getProductByOrderItem($orderItem);
    }

    /**
     * @param CreditMemoItem|InvoiceItem|OrderItem|QuoteItem $orderItem
     * @return ProductInterface
     * @throws NoSuchEntityException
     */
    private function getProductByOrderItem($orderItem)
    {
        $productId = $this->getProductIdByOrderItem($orderItem);

        return $this->getProductById($productId);
    }

    /**
     * @param CreditMemoItem|InvoiceItem|OrderItem|QuoteItem $orderItem
     * @return mixed
     */
    private function getProductIdByOrderItem($orderItem)
    {
        return $orderItem->getData(RealProductPuqConfigInterface::PRODUCT_ID);
    }

    /**
     * @param int $productId
     * @return ProductInterface
     * @throws NoSuchEntityException
     */
    private function getProductById($productId)
    {
        return $this->productRepository->getById($productId);
    }

    /**
     * @param ProductInterface $product
     * @param int $storeId
     * @return string
     */
    private function getPriceAndUnitsHtmlByProductAndStoreId(ProductInterface $product, $storeId)
    {
        $productId = $product->getId();

        return $this->getPriceAndUnitsHtmlByProductIdAndStoreId($productId, $storeId);
    }

    /**
     * @param int $productId
     * @param int $storeId
     * @return string
     */
    private function getPriceAndUnitsHtmlByProductIdAndStoreId($productId, $storeId)
    {
        return $this->aitocUnitsHelper->getPriceAndUnitsHtml($productId, $storeId);
    }
}
