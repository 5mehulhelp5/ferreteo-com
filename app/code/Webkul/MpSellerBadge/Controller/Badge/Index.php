<?php
/**
 * @category   Webkul
 * @package    Webkul_MpSellerBadge
 * @author     Webkul Software Private Limited
 * @copyright  Copyright (c)  Webkul Software Private Limited (https://webkul.com)
 * @license    https://store.webkul.com/license.html
 */

namespace Webkul\MpSellerBadge\Controller\Badge;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Webkul\Marketplace\Helper\Data
     */
    protected $marketplaceHelper;

    /**
     * @var \Webkul\MpSellerBadge\Helper\Data
     */
    protected $badgHelper;

    /**
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     * @param \Webkul\Marketplace\Helper\Data $marketplaceHelper
     * @param \Webkul\MpSellerBadge\Helper\Data $badgHelper
     */

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Webkul\Marketplace\Helper\Data $marketplaceHelper,
        \Webkul\MpSellerBadge\Helper\Data $badgHelper
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->marketplaceHelper  = $marketplaceHelper;
        $this->badgHelper  = $badgHelper;
        parent::__construct($context);
    }

    /**
     * Marketplace Seller's Product Collection Page.
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        if (!$this->marketplaceHelper->getSellerProfileDisplayFlag() ||
            !$this->badgHelper->badgeEnable()
        ) {
            $this->getRequest()->initForward();
            $this->getRequest()->setActionName('noroute');
            $this->getRequest()->setDispatched(false);

            return false;
        }
        $shopUrl = $this->marketplaceHelper->getProfileUrl();
        if (!$shopUrl) {
            $shopUrl = $this->getRequest()->getParam('shop');
        }
        if ($shopUrl) {
            $data = $this->marketplaceHelper->getSellerDataByShopUrl($shopUrl);
            if ($data->getSize()) {
                $shopUrl = $this->badgHelper->getShopName($shopUrl);
                $resultPage = $this->resultPageFactory->create();
                $resultPage->getConfig()->getTitle()->set(
                    __('%1 Badge(s)', $shopUrl)
                );
                return $resultPage;
            }
        }

        return $this->resultRedirectFactory->create()->setPath(
            'marketplace',
            ['_secure' => $this->getRequest()->isSecure()]
        );
    }
}
