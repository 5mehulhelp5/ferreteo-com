<?php
/**
 * Webkul Software
 *
 * @category  Webkul
 * @package   Webkul_MpVendorRegistration
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\MpVendorRegistration\Block\Seller;

class Register extends \Magento\Framework\View\Element\Template
{
    protected $helper;
    /**
     * @var Magento\Framework\Json\Helper\Data
     */
    protected $jsonData;
    /**
     * @var Webkul\Marketplace\Helper\Data
     */
    protected $mpHelper;
    /**
     * @var Magento\Customer\Helper\Address
     */
    protected $addressHelper;
    /**
     * Undocumented function
     *
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Webkul\MpVendorRegistration\Helper\Data $helper
     * @param \Magento\Customer\Helper\Address $addressHelper
     * @param \Webkul\Marketplace\Helper\Data $mpHelper
     * @param \Magento\Framework\Json\Helper\Data $jsonData
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Webkul\MpVendorRegistration\Helper\Data $helper,
        \Magento\Customer\Helper\Address $addressHelper,
        \Webkul\Marketplace\Helper\Data $mpHelper,
        \Magento\Framework\Json\Helper\Data $jsonData,
        array $data = []
    ) {
        $this->helper = $helper;
        $this->jsonData = $jsonData;
        $this->addressHelper = $addressHelper;
        $this->mpHelper = $mpHelper;
        parent::__construct($context, $data);
    }

    public function canShowAddress()
    {
        return $this->helper->getConfigData('show_address');
    }
    /**
     * @return marketplaceHelper
     */
    public function getMarketplaceHelper()
    {
        return $this->mpHelper;
    }
    /**
     * @return vendor registration helper
     */
    public function currentHelper()
    {
        return $this->helper;
    }
    /**
     * @return json helper
     */
    public function getJsonHelper()
    {
        return $this->jsonData;
    }
    /**
     * @return address helper
     */
    public function getAddressHelper()
    {
        return $this->addressHelper;
    }
}
