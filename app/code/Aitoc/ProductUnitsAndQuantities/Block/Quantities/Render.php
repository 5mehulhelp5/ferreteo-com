<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Block\Quantities;

use Aitoc\ProductUnitsAndQuantities\Api\Data\RealProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\OrderItemPuqConfigRepositoryInterface as PuqOrderItemRepository;
use Aitoc\ProductUnitsAndQuantities\Helper\Data as UnitsAndQuantitiesHelper;
use Aitoc\ProductUnitsAndQuantities\Model\OrderItemPuqConfig as OrderItemModel;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Model\Order\ItemRepository as OrderItemRepository;
use Magento\Store\Api\Data\StoreInterface;

/**
 * Class Render
 */
class Render extends Template
{
    /** @var UnitsAndQuantitiesHelper */
    protected $helper;

    /** @var OrderItemRepository */
    protected $orderItemRepository;

    /** @var PuqOrderItemRepository */
    protected $puqOrderItemRepository;

    /** @var OrderItemModel */
    private $productUnitsAndQuantitiesModel;

    /** @var ProductRepository */
    private $productRepository;

    /**
     * Render constructor.
     * @param Context $context
     * @param UnitsAndQuantitiesHelper $helper
     * @param OrderItemRepository $orderItemRepository
     * @param OrderItemModel $productUnitsAndQuantitiesModel
     * @param PuqOrderItemRepository $puqOrderItemRepository
     * @param ProductRepository $productRepository
     * @param array $data
     */
    public function __construct(
        Context $context,
        UnitsAndQuantitiesHelper $helper,
        OrderItemRepository $orderItemRepository,
        OrderItemModel $productUnitsAndQuantitiesModel,
        PuqOrderItemRepository $puqOrderItemRepository,
        ProductRepository $productRepository,
        array $data = []
    )
    {
        $this->helper = $helper;
        $this->orderItemRepository = $orderItemRepository;
        $this->puqOrderItemRepository = $puqOrderItemRepository;
        $this->productUnitsAndQuantitiesModel = $productUnitsAndQuantitiesModel;
        $this->productRepository = $productRepository;
        parent::__construct($context, $data);
    }

    /**
     * @param string $mode
     * @param int|null $productId
     * @param int $storeId
     * @return string
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function getJsonEncodedProductParamsOld($mode, $productId = null, $storeId = 0)
    {
        $productParams = $this->getProductConfigWithMode($mode, $productId, $storeId);

        return json_encode($productParams);
    }

    /**
     * @param string $mode
     * @param int|null $productId
     * @param int $storeId
     * @return array
     * @throws InputException
     * @throws NoSuchEntityException
     */
    private function getProductConfigWithMode($mode, $productId = null, $storeId = 0)
    {
        $product = $productId
            ? $this->getProductById($productId)
            : $this->getCurrentProduct();

        return $this->getProductConfig($product, $storeId, $mode);
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
     * @return ProductInterface
     * @throws NoSuchEntityException
     */
    private function getCurrentProduct()
    {
        $currentProductId = $this->getCurrentProductId();

        return $this->productRepository->getById($currentProductId);
    }

    /**
     * @return int
     */
    private function getCurrentProductId()
    {
        $params = $this->getRequest()->getParams();
        return isset($params[RealProductPuqConfigInterface::PRODUCT_ID])
            ? $params[RealProductPuqConfigInterface::PRODUCT_ID]
            : $params['id'];
    }

    /**
     * @param ProductInterface $product
     * @param int $storeId
     * @param string $mode
     * @return array
     * @throws InputException
     * @throws NoSuchEntityException
     */
    private function getProductConfig(ProductInterface $product, $storeId, $mode)
    {
        if ($byProductMode = $this->getModeByProduct($product)) {
            $mode = $byProductMode;
        }

        return $this->helper->getProductParamsWithMode($product->getId(), $storeId, $mode, $product);
    }

    /**
     * @param ProductInterface $product
     * @return null|string
     */
    private function getModeByProduct(ProductInterface $product)
    {
        return $this->helper->getModeByProduct($product);
    }

    /**
     * @param int $productId
     * @return string
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function getJsonEncodedProductConfigModeCart($productId)
    {
        $product = $this->getProductById($productId);

        $storeId = $this->getCurrentStoreId();
        $productParams = $this->getProductConfigModeCart($product, $storeId);

        return json_encode($productParams);
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
        return $this->_storeManager->getStore();
    }

    /**
     * @param ProductInterface $product
     * @param int $storeId
     * @return array
     * @throws InputException
     * @throws NoSuchEntityException
     */
    private function getProductConfigModeCart(ProductInterface $product, $storeId)
    {
        return $this->helper->getProductParamsModeCartByProduct($product, $storeId);
    }

    /**
     * @return null|string
     * @throws NoSuchEntityException
     */
    public function getCurrentProductMode()
    {
        $product = $this->getCurrentProduct();

        return $this->getModeByProduct($product);
    }

    /**
     * @return array
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function getCurrentProductConfigModeProduct()
    {
        $product = $this->getCurrentProduct();
        $storeId = $this->getCurrentStoreId();

        return $this->getProductConfigModeProduct($product, $storeId);
    }

    /**
     * @param ProductInterface $product
     * @param int $storeId
     * @return array
     * @throws InputException
     * @throws NoSuchEntityException
     */
    private function getProductConfigModeProduct(ProductInterface $product, $storeId)
    {
        return $this->helper->getProductParamsModeProductByProduct($product, $storeId);
    }
}
