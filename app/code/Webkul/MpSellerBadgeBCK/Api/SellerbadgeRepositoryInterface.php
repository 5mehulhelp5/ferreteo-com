<?php
/**
 * @category   Webkul
 * @package    Webkul_MpSellerBadge
 * @author     Webkul Software Private Limited
 * @copyright  Copyright (c)  Webkul Software Private Limited (https://webkul.com)
 * @license    https://store.webkul.com/license.html
 */
namespace Webkul\MpSellerBadge\Api;

/**
 * @api
 */
interface SellerbadgeRepositoryInterface
{
    /**
     * get seller badge collection by seller id.
     *
     * @param  int $customerId seller id
     *
     * @return object
     */
    public function getSellerBadgeCollectionBySellerId($customerId);

    /**
     * get a particular badge of a seller
     * @param  int $sellerId
     * @param  int $badgeId
     * @return object
     */
    public function getSellerBadgeCollection($sellerId, $badgeId);
}
