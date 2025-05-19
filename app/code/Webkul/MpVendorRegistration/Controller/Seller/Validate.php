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

namespace Webkul\MpVendorRegistration\Controller\Seller;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

class Validate extends Action
{
    protected $helper;
    
    /**
     * Undocumented function
     *
     * @param Context $context
     * @param \Webkul\MpVendorRegistration\Helper\Data $helper
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        \Webkul\MpVendorRegistration\Helper\Data $helper,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->helper = $helper;
        parent::__construct($context);
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function execute()
    {
        $result = [];
        $result["error"] = false;
        $result["reload"] = false;
        $result["msg"] = "Available";
        $email = trim($this->getRequest()->getParam("email"));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result["error"] = true;
            $result["reload"] = true;
            $result["msg"] = __("Enter Valid Email");
        } elseif ($this->helper->isUserExist($email)) {
            $result["error"] = true;
            $result["reload"] = true;
            $result["msg"] = __("Email id is already exist.");
        } else {
            $result["msg"] = __("Available");
        }

        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($result);
    }
}
