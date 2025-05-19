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
interface BadgeRepositoryInterface
{
    /**
     * get badge collection by entity id
     * @param  integer $entityId entity id
     * @return object
     */
    public function getBadgeCollectionById($entityId);

    /**
     * get existing badges
     * @param  array  $params contain badge ids
     * @return object
     */
    public function getExistingBadges($params = []);

    /**
     * check rank exist or not
     * @param  int $rank
     * @return bool
     */
    public function checkBadgeRankExist($rank);
}
