<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Ui\DataProvider\Product\Form\Modifier;

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\AclInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfigFieldIdsInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\ReplaceQtyInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\Data as UnitsAndQuantitiesHelper;
use Aitoc\ProductUnitsAndQuantities\Helper\ProductTypeHelper;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\GroupedProduct\Model\Product\Type\Grouped;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;

/**
 * Class AitocFields
 */
class ProductUnitsModifier implements ModifierInterface
{
    const CONTAINER_PATH_TEMPLATE = 'product-units-and-quantities/children/container_{code}/children/{code}';

    const DEFAULT_DATA_SOURCE = 'product';

    const USE_CONFIG = 'use_config';

    /**
     * @var ArrayManager
     */
    private $arrayManager;

    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    /**
     * @var ProductTypeHelper
     */
    private $productTypeHelper;

    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var UnitsAndQuantitiesHelper
     */
    private $helper;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param LocatorInterface $locator
     * @param ArrayManager $arrayManager
     * @param ScopeConfigInterface $scopeConfig
     * @param UnitsAndQuantitiesHelper $helper
     * @param AuthorizationInterface $authorization
     * @param ProductTypeHelper $productTypeHelper
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        LocatorInterface $locator,
        ArrayManager $arrayManager,
        ScopeConfigInterface $scopeConfig,
        UnitsAndQuantitiesHelper $helper,
        AuthorizationInterface $authorization,
        ProductTypeHelper $productTypeHelper,
        StoreManagerInterface $storeManager
    ) {
        $this->locator = $locator;
        $this->scopeConfig = $scopeConfig;
        $this->helper = $helper;
        $this->arrayManager = $arrayManager;
        $this->authorization = $authorization;
        $this->productTypeHelper = $productTypeHelper;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritDoc
     */
    public function modifyMeta(array $meta)
    {
        return $meta;
    }

    /**
     * {@inheritdoc}
     * @throws NoSuchEntityException
     */
    public function modifyData(array $data)
    {
        if (!$this->isAllowedForCurrentUser()) {
            return $data;
        }

        $currentProduct = $this->getCurrentProduct();

        if (!$this->isPuqApplicableForProductTypeId($currentProduct->getTypeId())) {
            return $data;
        }

        if ($currentProduct->getId()) {
            $storeId = $this->getCurrentStoreId();
            $this->addProductPuqData($currentProduct, $storeId, $data);
        }

        return $data;
    }

    /**
     * @return bool
     */
    private function isAllowedForCurrentUser()
    {
        return $this->authorization->isAllowed(AclInterface::DEFAULT_VALUE);
    }

    /**
     * @return ProductInterface
     */
    private function getCurrentProduct()
    {
        return $this->locator->getProduct();
    }

    /**
     * @param string $productTypeId
     * @return bool
     */
    private function isPuqApplicableForProductTypeId($productTypeId)
    {
        return $this->helper->isApplicableForProductTypeId($productTypeId);
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
     * @param ProductInterface $product
     * @param int $storeId
     * @param array $data
     */
    private function addProductPuqData(ProductInterface $product, $storeId, &$data)
    {
        $productId = $product->getId();
        $productPuqData = &$data[$productId][self::DEFAULT_DATA_SOURCE];
        $productPuqData = $this->getResultAdminProductPuqData($product, $storeId) + $productPuqData;
    }

    /**
     * @param ProductInterface $product
     * @param int $storeId
     * @return array
     */
    private function getResultAdminProductPuqData(ProductInterface $product, $storeId)
    {
        $resultAdminProductPuqData = $this->helper->getResultAdminPuqData($product->getId(), $storeId);

        if ($this->isOnOffReplaceQtyProductType($product)) {
            $resultAdminProductPuqData = $this->applyOnOffReplaceQtyValue($resultAdminProductPuqData);
        }

        return $resultAdminProductPuqData;
    }

    /**
     * @param ProductInterface $product
     * @return bool
     */
    private function isOnOffReplaceQtyProductType(ProductInterface $product)
    {
        return $product->getTypeId() === Grouped::TYPE_CODE;
    }

    /**
     * @param array $resultAdminProductPuqData
     * @return array
     */
    private function applyOnOffReplaceQtyValue($resultAdminProductPuqData)
    {
        $srcReplaceQtyValue = $resultAdminProductPuqData[PuqConfigFieldIdsInterface::REPLACE_QTY];
        $onOfValues = [ReplaceQtyInterface::ON, ReplaceQtyInterface::OFF];

        if (!in_array($srcReplaceQtyValue, $onOfValues)) {
            $resultAdminProductPuqData[PuqConfigFieldIdsInterface::REPLACE_QTY] = [ReplaceQtyInterface::ON];
        }

        return $resultAdminProductPuqData;
    }
}
