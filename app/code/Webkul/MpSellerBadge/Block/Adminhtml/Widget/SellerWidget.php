<?php
/**
 * @category   Webkul
 * @package    Webkul_MpSellerBadge
 * @author     Webkul Software Private Limited
 * @copyright  Copyright (c)  Webkul Software Private Limited (https://webkul.com)
 * @license    https://store.webkul.com/license.html
 */

namespace Webkul\MpSellerBadge\Block\Adminhtml\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Webkul\Marketplace\Model\ResourceModel\Orders\CollectionFactory as MpOrdersCollectionFactory;
use Webkul\Marketplace\Model\ResourceModel\Saleslist\CollectionFactory as MpSaleslistCollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;

class SellerWidget extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    protected $_template = 'mpsellerbadge/widget/SellerbadgeWidget.phtml';

    /**
     * construct description
     * @param MagentoFrameworkViewElementTemplateContext $context
     * $data[]
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Webkul\Marketplace\Model\ProductFactory $product,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute $entityAttribute,
        \Webkul\Marketplace\Helper\Data $helper,
        \Webkul\MpSellerBadge\Helper\Data $sellerbadgehelper,
        MpOrdersCollectionFactory $mpOrdersCollectionFactory,
        MpSaleslistCollectionFactory $mpSaleslistCollectionFactory,
        ProductCollectionFactory $productCollectionFactory,
        \Webkul\MpSellerBadge\Model\SellerbadgeFactory $sellerBadge,
        \Magento\Catalog\Helper\Image $imageHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->imageHelper = $imageHelper;
        $this->sellerBadge = $sellerBadge;
        $this->product = $product;
        $this->resource = $resource;
        $this->helper = $helper;
        $this->sellerbadgehelper = $sellerbadgehelper;
        $this->entityAttribute = $entityAttribute;
        $this->mpOrdersCollectionFactory = $mpOrdersCollectionFactory;
        $this->mpSaleslistCollectionFactory = $mpSaleslistCollectionFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->_productRepository = $productRepository;
    }

    /**
     * returns image helper object
     *
     * @return object
     */
    public function imageHelperObj()
    {
        return $this->imageHelper;
    }

    /**
     * returns image helper object
     *
     * @return object
     */
    public function mpHelperObj()
    {
        return $this->helper;
    }

    /**
     * returns image helper object
     *
     * @return object
     */
    public function mpsellerbadgeHelperObj()
    {
        return $this->sellerbadgehelper;
    }
    
    /**
     * returns selected seller's data(top products, profile, total product count, badges)
     *
     * @return mixed array
     */
    public function getSellers()
    {
        $sellers =  $this->getData('sellers');
        $seller_arr = explode(',', $sellers);
        $sellerBadges = $this->sellerBadge->create()->getCollection();
        $badgeTable = $sellerBadges->getTable('mpbadges');
        $productTable = $sellerBadges->getTable('marketplace_product');
        $sellerBadges->getSelect()->join(
            ['mb' => $badgeTable],
            'main_table.badge_id = mb.entity_id',
            [
                'badge_name' => 'mb.badge_name',
                'badge_desc' => 'mb.badge_description',
                'badge_url' => 'mb.badge_image_url'
            ]
        )->where('main_table.seller_id IN (?)', $seller_arr);
        $marketplaceUserdata = $this->resource->getTableName('marketplace_userdata');
        $catalogProductEntityInt = $this->resource->getTableName('catalog_product_entity_int');
        $marketplaceProduct = $this->resource->getTableName('marketplace_product');
        $catalogProductWebsite = $this->resource->getTableName('catalog_product_website');
        $proAttId = $this->entityAttribute->getIdByCode('catalog_product', 'visibility');
        $helper = $this->helper;
        $storeId = $this->getStoreId();
        $websiteId = $helper->getWebsiteId();
        $joinTable = $this->resource->getTableName('customer_grid_flat');

        $sellerArr = [];
        $sellerIdsArr = [];
        $sellerCountArr = [];
        foreach ($seller_arr as $value) {
            $sellerId = $value;
            if ($sellerHelperProCount = $helper->getSellerProCount($sellerId)) {
                $sellerArr[$sellerId] = [];
                array_push($sellerIdsArr, $sellerId);
                $sellerCountArr[$sellerId] = [];
                array_push($sellerCountArr[$sellerId], $sellerHelperProCount);
                $sellerProducts = $this->mpSaleslistCollectionFactory->create()
                ->addFieldToSelect('mageproduct_id')
                ->addFieldToSelect('magequantity')
                ->addFieldToSelect('seller_id')
                ->addFieldToSelect('cpprostatus');
                $sellerProducts->getSelect()
                ->join(
                    ['mpro' => $marketplaceProduct],
                    'mpro.mageproduct_id = main_table.mageproduct_id',
                    ['status' => 'status']
                )->where(
                    'main_table.seller_id='.$sellerId.' 
                    AND main_table.cpprostatus=1 
                    AND mpro.status = 1'
                );
                $sellerProducts->getSelect()
                ->columns('SUM(magequantity) as countOrderedProduct')
                ->group('mageproduct_id');
                $sellerProducts->setOrder('countOrderedProduct', 'DESC');
                $sellerProducts->getSelect()
                ->join(
                    ['cpei' => $catalogProductEntityInt],
                    'cpei.entity_id = main_table.mageproduct_id',
                    ['value' => 'value']
                )->where(
                    'cpei.value=4 
                    AND cpei.attribute_id = '.$proAttId.' 
                    AND cpei.store_id = '.$storeId
                );

                $sellerProducts->getSelect()->limit(3);
                foreach ($sellerProducts as $sellerProduct) {
                    array_push(
                        $sellerArr[$sellerId],
                        $sellerProduct['mageproduct_id']
                    );
                }
                if ((count($sellerProducts) < 3) && $storeId != 0) {
                    $sellerProducts = $this->mpSaleslistCollectionFactory->create()
                    ->addFieldToSelect('mageproduct_id')
                    ->addFieldToSelect('magequantity')
                    ->addFieldToSelect('seller_id')
                    ->addFieldToSelect('cpprostatus');
                    $sellerProducts->getSelect()
                    ->join(
                        ['mpro' => $marketplaceProduct],
                        'mpro.mageproduct_id = main_table.mageproduct_id',
                        ['status' => 'status']
                    );
                    if (count($sellerArr[$sellerId])) {
                        $sellerProducts->getSelect()->where(
                            'main_table.seller_id='.$sellerId.'
                            AND main_table.mageproduct_id NOT IN ('.implode(',', $sellerArr[$sellerId]).')
                            AND main_table.cpprostatus=1
                            AND mpro.status = 1'
                        );
                    } else {
                        $sellerProducts->getSelect()->where(
                            'main_table.seller_id='.$sellerId.'
                            AND main_table.cpprostatus=1
                            AND mpro.status = 1'
                        );
                    }
                    $sellerProducts->getSelect()
                    ->columns('SUM(magequantity) as countOrderedProduct')
                    ->group('mageproduct_id');
                    $sellerProducts->setOrder('countOrderedProduct', 'DESC');

                    $sellerProducts->getSelect()
                    ->join(
                        ['cpei' => $catalogProductEntityInt],
                        'cpei.entity_id = main_table.mageproduct_id',
                        ['value' => 'value']
                    )->where(
                        'cpei.value$storeUrl = $this->_storeManager->getStore()->getBaseUrl(
                            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                        )=4 
                        AND cpei.attribute_id = '.$proAttId.' 
                        AND cpei.store_id = 0'
                    );
                    $remaingCount = 3 - count($sellerArr[$sellerId]);
                    $sellerProducts->getSelect()->limit($remaingCount);
                    foreach ($sellerProducts as $sellerProduct) {
                        array_push(
                            $sellerArr[$sellerId],
                            $sellerProduct['mageproduct_id']
                        );
                    }
                }

                if (count($sellerArr[$sellerId]) < 3) {
                    $sellerProCount = count($sellerArr[$sellerId]);
                    $sellerProductColl = $this->productCollectionFactory->create()
                    ->addFieldToFilter(
                        'status',
                        ['eq' => 1]
                    )->addFieldToFilter(
                        'visibility',
                        ['eq' => 4]
                    );
                    if (count($sellerArr[$sellerId])) {
                        $sellerProductColl->getSelect()
                        ->addFieldToFilter(
                            'entity_id',
                            ['nin' => $sellerArr[$sellerId]]
                        );
                    }
                    $sellerProductColl->getSelect()
                    ->join(
                        ['cpw' => $catalogProductWebsite],
                        'cpw.product_id = e.entity_id'
                    )->where(
                        'cpw.website_id = '.$helper->getWebsiteId()
                    );
                    $sellerProductColl->getSelect()
                    ->join(
                        ['mpro' => $marketplaceProduct],
                        'mpro.mageproduct_id = e.entity_id',
                        [
                            'seller_id' => 'seller_id',
                            'mageproduct_id' => 'mageproduct_id'
                        ]
                    )->where(
                        'mpro.seller_id = '.$sellerId
                    );
                    $sellerProductColl->getSelect()->limit(3);
                    foreach ($sellerProductColl as $value) {
                        if ($sellerProCount < 3) {
                            array_push(
                                $sellerArr[$value['seller_id']],
                                $value['entity_id']
                            );
                            ++$sellerProCount;
                        }
                    }
                }
            } else {
                $sellerArr[$sellerId] = [];
                $sellerCountArr[$sellerId] = [0];
            }
        }
        $sellerProfileArr =  [];
        foreach ($seller_arr as $sellerId) {
            $sellerData = $helper->getSellerCollectionObj($sellerId);
            foreach ($sellerData as $sellerDataResult) {
                $sellerId = $sellerDataResult->getSellerId();
                $sellerProfileArr[$sellerId] = [];
                $profileurl = $sellerDataResult->getShopUrl();
                $shoptitle = $sellerDataResult->getShopTitle();
                $logo = $sellerDataResult->getLogoPic()??"noimage.png";
                array_push(
                    $sellerProfileArr[$sellerId],
                    [
                        'profileurl'=>$profileurl,
                        'shoptitle'=>$shoptitle,
                        'logo'=>$logo
                    ]
                );
            }
        }
        $badgeArr = [];
        foreach ($seller_arr as $arr) {
            $badgeArr[$arr] = [];
        }
        foreach ($sellerBadges->getData() as $sellerBadge) {
            $storeUrl = $this->_storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            );
            $sellerBadge['badge_url'] = $storeUrl.$sellerBadge['badge_url'];
            array_push($badgeArr[$sellerBadge['seller_id']], $sellerBadge);
        }
        return [$sellerArr, $sellerProfileArr, $sellerCountArr, $badgeArr];
    }

    /**
     * Undocumented function
     *
     * @param int $productId
     * @return object product
     */
    public function getProduct($productId)
    {
        return $this->_productRepository->getById($productId);
    }
    
    /**
     * get Store ID
     *
     * @return int
     */
    public function getStoreId()
    {
        if (count($this->helper->getAllStores()) == 1 && count($this->helper->getAllWebsites()) == 1) {
            $storeId = 0;
        } else {
            $storeId = $this->helper->getCurrentStoreId();
        }

        return $storeId;
    }
}
