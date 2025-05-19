<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_MpVendorRegistration
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\MpVendorRegistration\Model;

use Webkul\MpVendorRegistration\Api\Data\VendorRegistrationGroupInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class VendorRegistrationGroup extends AbstractModel implements VendorRegistrationGroupInterface, IdentityInterface
{

    const CACHE_TAG = 'vendorregistration_group';
    /**#@+
     * Block's statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

     /**#@-*/
    /**
     * @var string
     */
    protected $_cacheTag = 'vendorregistration_group';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'vendorregistration_group';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Webkul\MpVendorRegistration\Model\ResourceModel\VendorRegistrationGroup::class);
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG . '_' . $this->getIdentifier()];
    }

    /**
     * Get ID.
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Get group name.
     *
     * @return int|null
     */
    public function getGroupName()
    {
        return $this->getData(self::GROUP_NAME);
    }

    /**
     * Get status.
     *
     * @return int|null
     */
    public function getGroupStatus()
    {
        return $this->getData(self::GROUP_STATUS);
    }

    /**
     * Set ID.
     *
     * @return int|null
     */
    public function setId($id)
    {
        $this->setData(self::ENTITY_ID, $id);
    }

    /**
     * Set Attribute ID.
     *
     * @return int|null
     */
    public function setGroupName($name)
    {
        $this->setData(self::GROUP_NAME, $name);
    }

    /**
     * Set Show in Front.
     *
     * @return int|null
     */
    public function setGroupStatus($status)
    {
        $this->setData(self::GROUP_STATUS, $status);
    }
    /**
     * Prepare block's statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enable'), self::STATUS_DISABLED => __('Disable')];
    }
}
