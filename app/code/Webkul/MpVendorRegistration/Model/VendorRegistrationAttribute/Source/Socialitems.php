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

class Socialitems implements OptionSourceInterface
{
    /**
     * @var \Webkul\MpVendorRegistration\Model\VendorRegistrationAttributeFactory
     */
    protected $vendorAttributeFactory;

    /**
     * Constructor
     *
     * @param Webkul\MpVendorRegistration\Model\VendorRegistrationAttributeFactory $vendorGroupFactory
     */
    public function __construct(
        \Webkul\MpVendorRegistration\Model\VendorRegistrationAttributeFactory $vendorAttributeFactory
    ) {
        $this->vendorAttributeFactory = $vendorAttributeFactory;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableAttribute = $this->vendorAttributeFactory->create()
            ->getCollection()
            ->addFieldToFilter('attribute_id', ['eq' => 0])
            ->addFieldToFilter('attribute_code', ['like' => '%_id%']);
        $options = [];
        foreach ($availableAttribute as $value) {
            $options[] = [
                'label' => $value->getAttributeLabel(),
                'value' => $value->getAttributeCode(),
            ];
        }
        return $options;
    }
}
