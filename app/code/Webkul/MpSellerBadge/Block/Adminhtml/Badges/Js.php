<?php
/**
 * @category   Webkul
 * @package    Webkul_MpSellerBadge
 * @author     Webkul Software Private Limited
 * @copyright  Copyright (c)  Webkul Software Private Limited (https://webkul.com)
 * @license    https://store.webkul.com/license.html
 */
namespace Webkul\MpSellerBadge\Block\Adminhtml\Badges;

class Js extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;
    
    /**
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     */

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->jsonHelper = $jsonHelper;
    }

    /**
     * This function will return json encoded data
     *
     * @param json $data
     * @return Array
     */
    public function jsonEncodeData($data)
    {
        return $this->jsonHelper->jsonEncode($data, true);
    }
}
