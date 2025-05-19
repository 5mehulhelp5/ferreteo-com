<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Helper;

use Aitoc\ProductUnitsAndQuantities\Api\Data\ProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\PuqConfigBaseInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\ResultAdminProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\ResultFrontendProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\AllowUnitsInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\BlockConfigModeInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\ReplaceQtyInterface;
use Aitoc\ProductUnitsAndQuantities\Api\OrderItemPuqConfigRepositoryInterface as PuqOrderItemRepository;
use Aitoc\ProductUnitsAndQuantities\Api\PuqConfigurationInterface;
use Aitoc\ProductUnitsAndQuantities\Api\RealProductPuqConfigRepositoryInterface;
use Aitoc\ProductUnitsAndQuantities\Api\ResultAdminProductPuqConfigRepositoryInterface;
use Aitoc\ProductUnitsAndQuantities\Api\ResultFrontendProductPuqConfigRepositoryInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\PuqConfig\Adjuster\ByAspect\Qty\ByIncrementsQtyAdjuster;
use Magento\Bundle\Api\Data\LinkInterface;
use Magento\Bundle\Api\ProductLinkManagementInterface;
use Magento\Bundle\Model\Product\Type as BundleProductType;
use Magento\Bundle\Model\ResourceModel\Selection\Collection;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Api\Data\OptionInterface;
use Magento\ConfigurableProduct\Api\OptionRepositoryInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\GroupedProduct\Model\Product\Link\CollectionProvider\Grouped;
use Magento\GroupedProduct\Model\Product\Type\Grouped as GroupedProductType;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 */
class Data extends AbstractHelper
{
    const KEY_MODE = 'mode';

    /**
     * @var RealProductPuqConfigRepositoryInterface
     */
    private $realAdminProductPuqConfigRepository;

    /**
     * @var Grouped
     */
    private $groupedProductsProvider;

    /**
     * @var ProductLinkManagementInterface
     */
    private $productLinkManagement;

    /**
     * @var ByIncrementsQtyAdjuster
     */
    private $byIncrementsQtyAdjuster;

    /** @var OrderItemRepositoryInterface */
    private $orderItemRepository;

    /** @var PuqOrderItemRepository */
    private $puqOrderItemRepository;

    /** @var PuqConfigurationInterface */
    private $systemPuqConfigHelper;

    /** @var ResultFrontendProductPuqConfigRepositoryInterface */
    private $resultFrontendProductPuqConfigRepository;

    /** @var ResultAdminProductPuqConfigRepositoryInterface */
    private $resultAdminProductPuqConfigRepository;

    private $useConfigKeyBuilder;

    /**
     * @var OptionRepositoryInterface
     */
    private $optionRepository;

    /**
     * Data constructor.
     * @param Context $context
     * @param RealProductPuqConfigRepositoryInterface $realAdminProductPuqConfigRepository
     * @param Grouped $groupedProductsProvider
     * @param ByIncrementsQtyAdjuster $byIncrementsQtyAdjuster
     * @param ProductLinkManagementInterface $productLinkManagement
     * @param OrderItemRepositoryInterface $orderItemRepository
     * @param PuqOrderItemRepository $puqOrderItemRepository
     * @param PuqConfigurationInterface $systemPuqConfigHelper
     * @param ResultFrontendProductPuqConfigRepositoryInterface $resultFrontendProductPuqConfigRepository
     * @param ResultAdminProductPuqConfigRepositoryInterface $resultAdminProductPuqConfigRepository
     * @param UseConfigKeyBuilder $useConfigKeyBuilder
     * @param OptionRepositoryInterface $optionRepository
     */
    public function __construct(
        Context $context,
        RealProductPuqConfigRepositoryInterface $realAdminProductPuqConfigRepository,
        Grouped $groupedProductsProvider,
        ByIncrementsQtyAdjuster $byIncrementsQtyAdjuster,
        ProductLinkManagementInterface $productLinkManagement,
        OrderItemRepositoryInterface $orderItemRepository,
        PuqOrderItemRepository $puqOrderItemRepository,
        PuqConfigurationInterface $systemPuqConfigHelper,
        ResultFrontendProductPuqConfigRepositoryInterface $resultFrontendProductPuqConfigRepository,
        ResultAdminProductPuqConfigRepositoryInterface $resultAdminProductPuqConfigRepository,
        UseConfigKeyBuilder $useConfigKeyBuilder,
        OptionRepositoryInterface $optionRepository
    ) {
        parent::__construct($context);

        $this->realAdminProductPuqConfigRepository = $realAdminProductPuqConfigRepository;
        $this->groupedProductsProvider = $groupedProductsProvider;
        $this->byIncrementsQtyAdjuster = $byIncrementsQtyAdjuster;
        $this->productLinkManagement = $productLinkManagement;
        $this->orderItemRepository = $orderItemRepository;
        $this->puqOrderItemRepository = $puqOrderItemRepository;
        $this->systemPuqConfigHelper = $systemPuqConfigHelper;
        $this->resultFrontendProductPuqConfigRepository = $resultFrontendProductPuqConfigRepository;
        $this->resultAdminProductPuqConfigRepository = $resultAdminProductPuqConfigRepository;
        $this->useConfigKeyBuilder = $useConfigKeyBuilder;
        $this->optionRepository = $optionRepository;
    }

    /**
     * @param int $productId
     * @param int $storeId
     * @param string|null $mode
     * @param Product|null $product
     * @return array
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function getProductParamsWithMode($productId, $storeId, $mode = null, $product = null)
    {
        $puqProductConfigArray = $this->getPuqProductConfigArrayFixedByMinMaxIncrements($productId, $storeId);
        $this->setModeIfExists($puqProductConfigArray, $mode);
        $this->setLinkedProductsIfExists($puqProductConfigArray, $storeId, $product);

        return $puqProductConfigArray;
    }

    /**
     * @param int $productId
     * @param int $storeId
     * @return array
     */
    private function getPuqProductConfigArrayFixedByMinMaxIncrements($productId, $storeId)
    {
        $resultAdminConfig = $this->getResultAdminPuqConfigByProductId($productId, $storeId);
        $adjustedResultAdminConfig = $this->fixQtyMinMaxByIncrements($resultAdminConfig);

        return $adjustedResultAdminConfig->toArray();
    }

    /**
     * @param int $productId
     * @param int $storeId
     * @return ResultAdminProductPuqConfigInterface
     */
    private function getResultAdminPuqConfigByProductId($productId, $storeId)
    {
        return $this->resultAdminProductPuqConfigRepository->getByProductIdAndStoreId($productId, $storeId);
    }

    /**
     * @param PuqConfigBaseInterface $resultProductPuqConfig
     * @return PuqConfigBaseInterface
     */
    private function fixQtyMinMaxByIncrements(PuqConfigBaseInterface $resultProductPuqConfig)
    {
        $fixedResultProductPuqConfig = clone $resultProductPuqConfig;

        if (!$increment = $resultProductPuqConfig->getQtyIncrement()) {
            return $resultProductPuqConfig;
        }

        if ($startQty = $resultProductPuqConfig->getStartQty()) {
            $adjustedStartQty = $this->byIncrementsQtyAdjuster->getAdjustedMinValue($startQty, $increment);
            $fixedResultProductPuqConfig->setStartQty($adjustedStartQty);
        }

        if ($stopQty = $resultProductPuqConfig->getEndQty()) {
            $adjustedEndQty =  $this->byIncrementsQtyAdjuster->getAdjustedMaxValue($stopQty, $increment);
            $fixedResultProductPuqConfig->setEndQty($adjustedEndQty);
        }

        return $fixedResultProductPuqConfig;
    }

    /**
     * @param array $puqProductConfigArray
     * @param string $mode
     */
    private function setModeIfExists(&$puqProductConfigArray, $mode)
    {
        if ($mode) {
            $puqProductConfigArray['mode'] = $mode;
        }
    }

    /**
     * @param array $puqProductConfigArray
     * @param int $storeId
     * @param ProductInterface|Product|null $product
     * @throws InputException
     * @throws NoSuchEntityException
     */
    private function setLinkedProductsIfExists(&$puqProductConfigArray, $storeId, Product $product = null)
    {
        $linkedProductsKey = 'linkedProducts';

        if ($product && $this->isGroupedProduct($product)) {
            $puqProductConfigArray[$linkedProductsKey] = $this->getGroupedProductsConfig($product, $storeId);
        }

        if ($product && $this->isBundleProduct($product)) {
            $puqProductConfigArray[$linkedProductsKey] = $this->getBundleProductsConfig($product, $storeId);
        }

        if ($product && $this->isConfigurableProduct($product)) {
            $puqProductConfigArray[$linkedProductsKey] = $this->getConfigurableProductsConfig($product, $storeId);
        }
    }

    /**
     * @param ProductInterface $product
     * @return bool
     */
    private function isGroupedProduct(ProductInterface $product)
    {
        return $product->getTypeId() == GroupedProductType::TYPE_CODE;
    }

    /**
     * @param Product $product
     * @param int $storeId
     * @return array
     * @throws InputException
     * @throws NoSuchEntityException
     */
    private function getGroupedProductsConfig(Product $product, $storeId)
    {
        $linkedProducts = $this->getGroupedProducts($product);

        return $this->getGroupedProductsParams($linkedProducts, $storeId);
    }

    /**
     * @param Product $product
     * @return array|Product[]
     */
    private function getGroupedProducts(Product $product)
    {
        return $this->groupedProductsProvider->getLinkedProducts($product);
    }

    /**
     * @param $products
     * @param int $storeId
     * @return array
     * @throws InputException
     * @throws NoSuchEntityException
     */
    private function getGroupedProductsParams($products, $storeId)
    {
        $ret = [];

        /** @var ProductInterface|LinkInterface $product */
        foreach ($products as $key => $product) {
            $productId = $product->getId();
            $productParams = $this->getProductParamsWithMode($productId, $storeId, null, $product);
            $ret[$productId] = $productParams;
        }

        return $ret;
    }

    /**
     * @param ProductInterface $product
     * @return bool
     */
    private function isBundleProduct(ProductInterface $product)
    {
        return $product->getTypeId() == BundleProductType::TYPE_CODE;
    }

    /**
     * @param Product $product
     * @param string $storeId
     * @return array
     * @throws InputException
     * @throws NoSuchEntityException
     */
    private function getBundleProductsConfig(Product $product, $storeId)
    {
        $bundleProducts = $this->getBundleProducts($product);

        return $this->getBundleProductsParams($bundleProducts, $storeId);
    }

    /**
     * @param Product $product
     * @return Collection
     */
    private function getBundleProducts(Product $product)
    {
        /** @var BundleProductType $productTypeInstance */
        $productTypeInstance = $product->getTypeInstance();
        $optionIds = $productTypeInstance->getOptionsIds($product);
        $selectionCollection = $productTypeInstance->getSelectionsCollection($optionIds, $product);

        return $selectionCollection;
    }

    /**
     * @param $products
     * @param int $storeId
     * @return array
     * @throws InputException
     * @throws NoSuchEntityException
     */
    private function getBundleProductsParams($products, $storeId)
    {
        $ret = [];

        /** @var ProductInterface|LinkInterface $product */
        foreach ($products as $key => $product) {
            $productId = $product->getId();
            $productParams = $this->getProductParamsWithMode($productId, $storeId, null, $product);
            $ret[$key] = $productParams;
        }

        return $ret;
    }

    /**
     * @param ProductInterface $product
     * @return bool
     */
    private function isConfigurableProduct(ProductInterface $product)
    {
        return $product->getTypeId() == Configurable::TYPE_CODE;
    }

    /**
     * @param Product $product
     * @param int $storeId
     * @return array
     * @throws InputException
     * @throws NoSuchEntityException
     */
    private function getConfigurableProductsConfig(Product $product, $storeId)
    {
        $configurableProductIds = $this->getConfigurableProductIds($product);

        return $this->getConfigurableProductsParams($configurableProductIds, $storeId);
    }

    /**
     * @param Product $product
     * @return OptionInterface[]
     */
    private function getConfigurableProductIds(Product $product)
    {
        return $product->getTypeInstance()->getUsedProductIds($product);
    }

    /**
     * @param $productIds
     * @param int $storeId
     * @return array
     * @throws InputException
     * @throws NoSuchEntityException
     */
    private function getConfigurableProductsParams($productIds, $storeId)
    {
        $ret = [];

        //todo: implement by repository method
        /** @var ProductInterface|LinkInterface $product */
        foreach ($productIds as $productId) {
            $productParams = $this->getProductParamsWithMode($productId, $storeId,'configurable');
            $ret[$productId] = $productParams;
        }

        return $ret;
    }

    /**
     * @param ProductInterface $product
     * @param int $storeId
     * @return array
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function getProductParamsModeProductByProduct(ProductInterface $product, $storeId)
    {
        $mode = $this->getModeByProductTypeId($product->getTypeId());

        return $this->getProductParamsWithModeByProductAndStoreId($product, $storeId, $mode);
    }

    /**
     * @param string $productTypeId
     * @return string
     */
    private function getModeByProductTypeId($productTypeId)
    {
        switch ($productTypeId) {
            case GroupedProductType::TYPE_CODE:
                return BlockConfigModeInterface::GROUPED_VIEW;
            case BundleProductType::TYPE_CODE:
                return BlockConfigModeInterface::BUNDLE;
            case Configurable::TYPE_CODE:
                return BlockConfigModeInterface::CONFIGURABLE;
            default:
                return BlockConfigModeInterface::PRODUCT;
        }
    }

    /**
     * @param ProductInterface $product
     * @param int $storeId
     * @param string $mode
     * @return array
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function getProductParamsWithModeByProductAndStoreId(ProductInterface $product, $storeId, $mode)
    {
        $puqProductConfigArray = $this->getProductParamsByProductAndStoreId($product, $storeId);
        $puqProductConfigArray['mode'] = $mode;

        //products with 0 qty not added to cart for grouped products
        if ($mode === BlockConfigModeInterface::GROUPED_VIEW) {
            $this->addZeroToUseQuantitiesInLinkedProducts($puqProductConfigArray);
        }

        return $puqProductConfigArray;
    }

    /**
     * @param ProductInterface $product
     * @param int $storeId
     * @return array
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function getProductParamsByProductAndStoreId(ProductInterface $product, $storeId)
    {
        $puqProductConfigArray = $this->getPuqProductConfigArrayFixedByMinMaxIncrements($product->getId(), $storeId);
        $this->setLinkedProductsIfExists($puqProductConfigArray, $storeId, $product);

        return $puqProductConfigArray;
    }

    /**
     * @param array $puqProductConfigArray
     */
    public function addZeroToUseQuantitiesInLinkedProducts(&$puqProductConfigArray)
    {
        foreach ($puqProductConfigArray['linkedProducts'] as &$linkedProduct) {
            $linkedProduct[PuqConfigBaseInterface::USE_QUANTITIES]
                = '0,' . $puqProductConfigArray[PuqConfigBaseInterface::USE_QUANTITIES];
        }
    }

    /**
     * @param ProductInterface $product
     * @return null|string
     */
    public function getModeByProduct(ProductInterface $product)
    {
        $productTypeId = $product->getTypeId();

        return $this->getModeByProductTypeId($productTypeId);
    }

    /**
     * @param ProductInterface $product
     * @param int $storeId
     * @return array
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function getProductParamsModeCartByProduct(ProductInterface $product, $storeId)
    {
        return $this->getProductParamsWithModeByProductAndStoreId($product, $storeId, BlockConfigModeInterface::CART);
    }

    /**
     * @return bool
     */
    public function getIsEnabledForCurrentStoreByConfig()
    {
        return $this->getConfigFieldValue(ProductPuqConfigInterface::REPLACE_QTY) != ReplaceQtyInterface::OFF;
    }

    /**
     * @param string $field
     * @return mixed
     */
    public function getConfigFieldValue($field)
    {
        $field = 'product_units_and_quantities/general_settings/' . $field;
        $value = $this->scopeConfig->getValue($field, ScopeInterface::SCOPE_STORE);

        return $value;
    }

    /**
     * @param ProductInterface $product
     * @return bool
     */
    public function isApplicableForProduct(ProductInterface $product)
    {
        $productTypeId = $product->getTypeId();

        return $this->isApplicableForProductTypeId($productTypeId);
    }

    /**
     * @param string $productType
     * @return bool
     */
    public function isApplicableForProductTypeId($productType)
    {
        $applicableProductType = [
            GroupedProductType::TYPE_CODE,
            BundleProductType::TYPE_CODE,
            Configurable::TYPE_CODE,
            'simple',
            'downloadable'
        ];

        return in_array($productType, $applicableProductType);
    }

    /**
     * @param string $field
     * @return string
     */
    public function getUseConfigKey($field)
    {
        return $this->useConfigKeyBuilder->getUseConfigKey($field);
    }

    /**
     * @param int $productId
     * @param int $storeId
     * @return array
     */
    public function getResultAdminPuqData($productId, $storeId)
    {
        $resultProductPuqConfig = $this->getResultAdminPuqConfigByProductId($productId, $storeId);

        return $resultProductPuqConfig->toArray();
    }

    /**
     * @param int $productId
     * @param int $storeId
     * @return string
     */
    public function getPriceAndUnitsHtml($productId, $storeId)
    {
        $priceAndUnitsText = $this->getPriceAndUnitsText($productId, $storeId);

        return ' <span class="aitoc-puq-units">' . $priceAndUnitsText . '</span>';
    }

    /**
     * @param int $productId
     * @param int $storeId
     * @return string
     */
    public function getPriceAndUnitsText($productId, $storeId)
    {
        $resultProductConfig = $this->getResultProductPuqConfigByProductIdAndStockId($productId, $storeId);

        if ($resultProductConfig->getAllowUnits() == AllowUnitsInterface::NO) {
            return '';
        }

        $unit = $resultProductConfig->getPricePer();
        $divider = $resultProductConfig->getPricePerDivider();

        return $divider . ' ' . $unit;
    }

    /**
     * @param int $productId
     * @param int $storeId
     * @return ResultFrontendProductPuqConfigInterface
     */
    public function getResultProductPuqConfigByProductIdAndStockId($productId, $storeId)
    {
        return $this->resultFrontendProductPuqConfigRepository->getByProductIdAndStoreId($productId, $storeId);
    }

    /**
     * @param int $orderItemId
     * @return array
     */
    public function getOrderItemConfig($orderItemId)
    {
        $puqOrderItem = $this->puqOrderItemRepository->getByOrderItemId($orderItemId);

        $productParams = [];

        if ($puqOrderItem) {
            $productParams[ProductPuqConfigInterface::PRICE_PER] = $puqOrderItem->getPricePer();
            $productParams[ProductPuqConfigInterface::PRICE_PER_DIVIDER] = $puqOrderItem->getPricePerDivider();
        }

        return $productParams;
    }
}
