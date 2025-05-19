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

class VendorGroups implements OptionSourceInterface
{
    /**
     * @var \Webkul\MpVendorRegistration\Model\VendorRegistrationGroupFactory
     */
    protected $vendorGroupFactory;

    /**
     * Constructor
     *
     * @param Webkul\MpVendorRegistration\Model\VendorRegistrationGroupFactory $vendorGroupFactory
     */
    public function __construct(
        \Webkul\MpVendorRegistration\Model\VendorRegistrationGroupFactory $vendorGroupFactory
    ) {
        $this->vendorGroupFactory = $vendorGroupFactory;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableGroups = $this->vendorGroupFactory->create()
            ->getCollection()
            ->addFieldToFilter('group_status', ['eq' => 1])
            ->addFieldToFilter('group_code', ['neq' => 'addressinfo']);
        $options = [];
        foreach ($availableGroups as $value) {
            $options[] = [
                'label' => $value->getGroupName(),
                'value' => $value->getId(),
            ];
        }
        return $options;
    }
}
