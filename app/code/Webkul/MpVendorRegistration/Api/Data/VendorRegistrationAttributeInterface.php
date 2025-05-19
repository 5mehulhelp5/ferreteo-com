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
namespace Webkul\MpVendorRegistration\Api\Data;

interface VendorRegistrationAttributeInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ENTITY_ID = 'entity_id';
    const ATTRIBUTE_ID = 'attribute_id';
    const GROUP_ID = 'group_id';
    const IS_REQUIRED = 'is_required';
    const HAS_PARENT = 'has_parent';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get Attribute ID
     *
     * @return int|null
     */
    public function getAttributeId();

    /**
     * Get is required status
     *
     * @return int|null
     */
    public function getIsRequired();

    /**
     * Get Has Parent
     *
     * @return int|null
     */
    public function getHasParent();

    /**
     * Set ID
     *
     * @return int|null
     */
    public function setId($id);

    /**
     * Set Attribute ID
     *
     * @return int|null
     */
    public function setAttributeId($attributeId);

    /**
     * Set is required status
     *
     * @return int|null
     */
    public function setIsRequired($required);

    /**
     * Get Has Parent
     *
     * @return int|null
     */
    public function setHasParent($parent);
}
