<?php
/**
 * @category   Webkul
 * @package    Webkul_MpSellerBadge
 * @author     Webkul Software Private Limited
 * @copyright  Copyright (c)  Webkul Software Private Limited (https://webkul.com)
 * @license    https://store.webkul.com/license.html
 */
namespace Webkul\MpSellerBadge\Model;

use Webkul\MpSellerBadge\Api\Data\SellerbadgeRepositoryInterface;
use Webkul\MpSellerBadge\Model\ResourceModel\Badge\Collection;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class SellerbadgeRepository implements \Webkul\MpSellerBadge\Api\SellerbadgeRepositoryInterface
{
    /**
     * resource model
     * @var \Webkul\MpSellerBadge\Model\ResourceModel\Sellerbadge
     */
    protected $_resourceModel;

    /**
     * seller badge factory
     * @var SellerbadgeFactory
     */
    protected $_sellerBadgeFactory;

    /**
     * collection of seller badge
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @param SellerbadgeFactory                                                      $sellerBadgeFactory
     * @param \Webkul\MpSellerBadge\Model\ResourceModel\Sellerbadge\CollectionFactory $collectionFactory
     * @param \Webkul\MpSellerBadge\Model\ResourceModel\Sellerbadge                   $resourceModel
     */
    public function __construct(
        SellerbadgeFactory $sellerBadgeFactory,
        \Webkul\MpSellerBadge\Model\ResourceModel\Sellerbadge\CollectionFactory $collectionFactory,
        \Webkul\MpSellerBadge\Model\ResourceModel\Sellerbadge $resourceModel
    ) {
    
        $this->_resourceModel = $resourceModel;
        $this->_sellerBadgeFactory = $sellerBadgeFactory;
        $this->_collectionFactory = $collectionFactory;
    }

    /**
     * get badge collection of seller
     * @param  int $customerId
     */
    public function getSellerBadgeCollectionBySellerId($customerId)
    {
        $sellerBadgeCollection = $this->_collectionFactory->create()->addFieldToFilter(
            'seller_id',
            ['eq' => $customerId]
        );
        return $sellerBadgeCollection;
    }

    /**
     * get a badge collection of a seller
     * @param  int $sellerId
     * @param  int $badgeId
     * @return object
     */
    public function getSellerBadgeCollection($sellerId, $badgeId)
    {
        $sellerBadgeCollection = $this->_collectionFactory->create()->addFieldToFilter(
            'seller_id',
            ['in' => $sellerId]
        )->addFieldToFilter(
            'badge_id',
            ['eq' => $badgeId]
        );
        return $sellerBadgeCollection;
    }
}
