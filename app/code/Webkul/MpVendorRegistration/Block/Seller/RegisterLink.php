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

use Magento\Customer\Model\Context;

class RegisterLink extends \Magento\Framework\View\Element\Html\Link
{
    /**
     * Customer session
     *
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;

    /**
     * Undocumented function
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Webkul\MpVendorRegistration\Helper\Data $helper
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Webkul\MpVendorRegistration\Helper\Data $helper,
        \Magento\Framework\App\Http\Context $httpContext,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->httpContext = $httpContext;
        $this->helper = $helper;
    }
    /**
     * get url link
     *
     * @return urlLink
     */
    public function getHref()
    {
        return $this->getUrl("vendorregistration/seller/register");
    }
    /**
     * html data
     *
     * @return htmlData
     */
    protected function _toHtml()
    {
        if ($this->httpContext->getValue(Context::CONTEXT_AUTH)) {
            return '';
        }
        if (!$this->helper->getConfigData('visible_registration')) {
            return '';
        }

        return parent::_toHtml();
    }
}
