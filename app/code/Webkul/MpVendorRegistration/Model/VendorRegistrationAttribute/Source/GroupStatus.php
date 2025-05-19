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
namespace Webkul\MpVendorRegistration\Model\VendorRegistrationAttribute\Source;

use Magento\Framework\Data\OptionSourceInterface;

class GroupStatus implements OptionSourceInterface
{
    /**
     * @var \Webkul\MpVendorRegistration\Model\VendorRegistrationAttribute
     */
    protected $vendorGroup;

    /**
     * Constructor
     *
     * @param Webkul\MpVendorRegistration\Model\VendorRegistrationAttribute $vendorAttributeBlock
     */
    public function __construct(\Webkul\MpVendorRegistration\Model\VendorRegistrationGroup $vendorGroup)
    {
        $this->vendorGroup = $vendorGroup;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->vendorGroup->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
