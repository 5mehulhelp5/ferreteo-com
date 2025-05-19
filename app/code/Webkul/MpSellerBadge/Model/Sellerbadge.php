<?php
/**
 * @category   Webkul
 * @package    Webkul_MpSellerBadge
 * @author     Webkul Software Private Limited
 * @copyright  Copyright (c)  Webkul Software Private Limited (https://webkul.com)
 * @license    https://store.webkul.com/license.html
 */
namespace Webkul\MpSellerBadge\Model;

use Webkul\MpSellerBadge\Api\Data\SellerbadgeInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * MpSellerBadge Sellerbadge Model
 */
class Sellerbadge extends \Magento\Framework\Model\AbstractModel implements SellerbadgeInterface, IdentityInterface
{
    /**
     * No route page id
     */
    const NOROUTE_ENTITY_ID = 'no-route';

    /**#@+
     * badge's Statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;
    /**#@-*/

    /**
     * MpSellerBadge Sellerbadge cache tag
     */
    const CACHE_TAG = 'mpsellerbadge';

    /**
     * @var string
     */
    protected $_cacheTag = 'mpsellerbadge';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'mpsellerbadge';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Webkul\MpSellerBadge\Model\ResourceModel\Sellerbadge::class);
    }

    /**
     * Load object data
     *
     * @param int|null $id
     * @param string $field
     * @return $this
     */
    public function load($id, $field = null)
    {
        if ($id === null) {
            return $this->noRouteProduct();
        }
        return parent::load($id, $field);
    }

    /**
     * Load No-Route Product
     */
    public function noRouteProduct()
    {
        return $this->load(self::NOROUTE_ENTITY_ID, $this->getIdFieldName());
    }

    /**
     * Prepare statuses.
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enable'), self::STATUS_DISABLED => __('Disable')];
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getId()
    {
        return parent::getData(self::ENTITY_ID);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return \Webkul\MpSellerBadge\Api\Data\BadgeInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }
}
