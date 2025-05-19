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

/**
 * Class IsActive
 */
class Status implements OptionSourceInterface
{
    /**
     * @var \Webkul\MpVendorRegistration\Model\VendorRegistrationAttribute
     */
    protected $vendorAttributeBlock;

    /**
     * Constructor
     *
     * @param \Webkul\MpVendorRegistration\Model\VendorRegistrationAttribute $vendorAttributeBlock
     */
    public function __construct(\Webkul\MpVendorRegistration\Model\VendorRegistrationAttribute $vendorAttributeBlock)
    {
        $this->vendorAttributeBlock = $vendorAttributeBlock;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->vendorAttributeBlock->getAttrbiteStatuses();
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
