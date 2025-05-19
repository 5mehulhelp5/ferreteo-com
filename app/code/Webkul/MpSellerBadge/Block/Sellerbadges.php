<?php
/**
 * @category   Webkul
 * @package    Webkul_MpSellerBadge
 * @author     Webkul Software Private Limited
 * @copyright  Copyright (c)  Webkul Software Private Limited (https://webkul.com)
 * @license    https://store.webkul.com/license.html
 */
namespace Webkul\MpSellerBadge\Block;

use Webkul\MpSellerBadge\Api\BadgeRepositoryInterface;
use Webkul\MpSellerBadge\Api\SellerbadgeRepositoryInterface;
use Webkul\Marketplace\Helper\Data;
use Webkul\Marketplace\Model\Seller;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Sellerbadges extends \Magento\Framework\View\Element\Template implements ArgumentInterface
{
    /**
     * badge repository
     * @var badgeRepository
     */
    private $badgeRepository;

    /**
     * seller badge repository
     * @var SellerbadgeRepositoryInterface
     */
    private $sellerBadgeRepository;

    /**
     * marketplace hleper
     * @var Data
     */
    private $helperMarketplace;

    /**
     * seller model of marketplace
     * @var [type]
     */
    private $sellerModel;

    /**
     * @param Data                                      $helperMarketplace
     * @param Seller                                    $sellerModel
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Customer\Model\Session           $customerSession
     * @param \Magento\Catalog\Block\Product\Context    $context
     * @param BadgeRepositoryInterface                  $badgeRepository
     * @param SellerbadgeRepositoryInterface            $sellerBadgeRepository
     * @param array                                     $data
     */
    public function __construct(
        Data $helperMarketplace,
        Seller $sellerModel,
        \Magento\Catalog\Block\Product\Context $context,
        BadgeRepositoryInterface $badgeRepository,
        SellerbadgeRepositoryInterface $sellerBadgeRepository,
        array $data = []
    ) {
        $this->sellerModel = $sellerModel;
        $this->helperMarketplace = $helperMarketplace;
        $this->_storeManager = $context->getStoreManager();
        $this->badgeRepository = $badgeRepository;
        $this->sellerBadgeRepository = $sellerBadgeRepository;
        parent::__construct($context, $data);
    }

    /**
     * get loggedin Customer badges
     * @return array
     */
    public function getLoginSellerBadges()
    {
        $customerId = $this->helperMarketplace->getCustomerId();
        $sellerbadgeCollection = $this->sellerBadgeRepository->getSellerBadgeCollectionBySellerId($customerId);
        $badgeImage = [];
        foreach ($sellerbadgeCollection as $sellerBadge) {
            $sellerId = $sellerBadge->getSellerId();
            if ($customerId == $sellerId) {
                $badge=  $this->badgeRepository->getBadgeCollectionById($sellerBadge->getBadgeId());
                $badgeImagePrefix = $this->_storeManager->getStore()->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                );
                if ($badge->getStatus() == 1) {
                    $badgeImage[$badge->getRank()] = [
                        "url" => $badgeImagePrefix.$badge->getBadgeImageUrl(),
                        "desc" => $badge->getBadgeDescription(),
                        "title" => $badge->getBadgeName()
                    ];
                }
            }
        }
        return $badgeImage;
    }

    /**
     * get partner id
     * @return integer
     */
    public function getPartnerId()
    {
        $shopUrl = $this->helperMarketplace->getProfileUrl();
        if (isset($shopUrl)) {
            $shopUrl=$this->getRequest()->getParam('shop');
        }
        if ($shopUrl) {
            $data=$this->sellerModel->getCollection()
                    ->addFieldToFilter(
                        'shop_url',
                        ['eq'=>$shopUrl]
                    );
            foreach ($data as $seller) {
                return $seller->getSellerId();
            }
        }
    }
    
    /**
     * get seller badges
     * @return array
     */
    public function getSellerBadges()
    {
        $customerId = $this->getPartnerId();
        $sellerbadgeCollection = $this->sellerBadgeRepository->getSellerBadgeCollectionBySellerId($customerId);
        $badgeImage = [];
        foreach ($sellerbadgeCollection as $sellerBadge) {
            $sellerId = $sellerBadge->getSellerId();
            if ($customerId == $sellerId) {
                $badge = $this->badgeRepository->getBadgeCollectionById($sellerBadge->getBadgeId());
                $badgeImagePrefix = $this->_storeManager->getStore()->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                );
                if ($badge->getStatus() == 1) {
                    $badgeImage[$badge->getRank()] = [
                        "url" => $badgeImagePrefix.$badge->getBadgeImageUrl(),
                        "desc" => $badge->getBadgeDescription(),
                        "title" => $badge->getBadgeName()
                    ];
                }
            }
        }
        return $badgeImage;
    }

    /**
     * get seller
     *
     * @return object
     */
    public function getProfileDetail()
    {
        $shopUrl = $this->helperMarketplace->getProfileUrl();
        if (!$shopUrl) {
            $shopUrl = $this->getRequest()->getParam('shop');
        }
        if ($shopUrl) {
            $data = $this->helperMarketplace->getSellerCollectionObjByShop($shopUrl);
            foreach ($data as $seller) {
                return $seller;
            }
        }
    }

    /**
     * get seller badges
     * @return array
     */
    public function getSellerBadgesDescription($rankId)
    {
        $customerId = $this->getPartnerId();
        $sellerbadgeCollection = $this->sellerBadgeRepository->getSellerBadgeCollectionBySellerId($customerId);
        $badgeImage = [];
        foreach ($sellerbadgeCollection as $sellerBadge) {
            $sellerId = $sellerBadge->getSellerId();
            if ($customerId == $sellerId) {
                $badge = $this->badgeRepository->getBadgeCollectionById($sellerBadge->getBadgeId());
                if ($badge->getStatus() == 1 && $badge->getRank()== $rankId) {
                    $badgeDesc = $badge->getBadgeDescription();
                }
            }
        }
        return $badgeDesc;
    }

    /**
     * Seller Collection
     *
     * @return collection
     */
    public function getSeller()
    {
        return $this->helperMarketplace->getSeller();
    }

    /**
     * Get IsSeller  or not
     *
     * @return boolean
     */
    public function isSeller()
    {
        return $this->helperMarketplace->isSeller();
    }
}
