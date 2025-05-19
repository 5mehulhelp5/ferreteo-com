<?php
/**
 * @category   Webkul
 * @package    Webkul_MpSellerBadge
 * @author     Webkul Software Private Limited
 * @copyright  Copyright (c)  Webkul Software Private Limited (https://webkul.com)
 * @license    https://store.webkul.com/license.html
 */
namespace Webkul\MpSellerBadge\Model;

use Webkul\MpSellerBadge\Api\Data\BadgeRepositoryInterface;
use Webkul\MpSellerBadge\Model\ResourceModel\Badge\Collection;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class BadgeRepository implements \Webkul\MpSellerBadge\Api\BadgeRepositoryInterface
{
    /**
     * resource model
     * @var \Webkul\MpSellerBadge\Model\ResourceModel\Badge
     */
    protected $_resourceModel;

    /**
     * @param BadgeFactory                                                      $badgeFactory
     * @param \Webkul\MpSellerBadge\Model\ResourceModel\Badge\CollectionFactory $collectionFactory
     * @param \Webkul\MpSellerBadge\Model\ResourceModel\Badge                   $resourceModel
     */
    public function __construct(
        BadgeFactory $badgeFactory,
        \Webkul\MpSellerBadge\Model\ResourceModel\Badge\CollectionFactory $collectionFactory,
        \Webkul\MpSellerBadge\Model\ResourceModel\Badge $resourceModel
    ) {
    
        $this->_resourceModel = $resourceModel;
        $this->_badgeFactory = $badgeFactory;
        $this->_collectionFactory = $collectionFactory;
    }

    /**
     * get badges collection by entity id
     * @param  integer $entityId
     * @return object
     */
    public function getBadgeCollectionById($entityId)
    {
        $badgeCollection = $this->_badgeFactory->create()->load($entityId);
        return $badgeCollection;
    }
    
    /**
     * get existing badges
     * @param  array  $params contains badge ids
     * @return object
     */
    public function getExistingBadges($params = [])
    {
        $badgeCollection = $this->_collectionFactory->create()->addFieldToFilter(
            'entity_id',
            ['in'=>$params]
        );
        return $badgeCollection;
    }

    /**
     * check rank exist or not
     * @param  int $rank
     * @return bool
     */
    public function checkBadgeRankExist($rank)
    {
        $badgeCollection = $this->_collectionFactory->create()->addFieldToFilter(
            'rank',
            ['in'=>$rank]
        );
        if ($badgeCollection->getSize()) {
            return $badgeCollection->getFirstItem()->getId();
        } else {
            return false;
        }
    }
}
