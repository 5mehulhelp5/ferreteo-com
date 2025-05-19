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

use Webkul\MpVendorRegistration\Api\Data\VendorRegistrationAttributeInterface as Vrai;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class VendorRegistrationAttribute extends AbstractModel implements Vrai, IdentityInterface
{

    const CACHE_TAG = 'vendorregistration_block';
    /**#@+
     * Block's statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

     /**#@-*/
    /**
     * @var string
     */
    protected $_cacheTag = 'vendorregistration_block';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'vendorregistration_block';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Webkul\MpVendorRegistration\Model\ResourceModel\VendorRegistrationAttribute::class);
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
     * Get Attribute ID.
     *
     * @return int|null
     */
    public function getAttributeId()
    {
        return $this->getData(self::ATTRIBUTE_ID);
    }

    /**
     * Get is required.
     *
     * @return int|null
     */
    public function getIsRequired()
    {
        return $this->getData(self::IS_REQUIRED);
    }

    /**
     * Get Has Parent.
     *
     * @return int|null
     */
    public function getHasParent()
    {
        return $this->getData(self::ATTRIBUTE_ID);
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
    public function setAttributeId($attributeId)
    {
        $this->setData(self::ATTRIBUTE_ID, $attributeId);
    }

    /**
     * Set Show in Front.
     *
     * @return int|null
     */
    public function setIsRequired($required)
    {
        $this->setData(self::IS_REQUIRED, $required);
    }

    /**
     * Get Has Parent.
     *
     * @return int|null
     */
    public function setHasParent($parent)
    {
        $this->setData(self::HAS_PARENT, $parent);
    }

    /**
     * Prepare block's statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Yes'), self::STATUS_DISABLED => __('No')];
    }

    /**
     * Prepare block's statuses.
     *
     * @return array
     */
    public function getAttrbiteStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }
    /**
     * Prepare block's statuses.
     *
     * @return array
     */
    public function getIsRequiredStatus()
    {
        return [self::STATUS_ENABLED => __('Yes'), self::STATUS_DISABLED => __('No')];
    }
}
