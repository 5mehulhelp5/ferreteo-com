<?php
/**
 * @category   Webkul
 * @package    Webkul_MpSellerBadge
 * @author     Webkul Software Private Limited
 * @copyright  Copyright (c)  Webkul Software Private Limited (https://webkul.com)
 * @license    https://store.webkul.com/license.html
 */
namespace Webkul\MpSellerBadge\Helper;

/**
 * MpSellerBadge data helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     *
     * @param \Magento\Framework\App\Helper\Context     $context
     * @param \Webkul\Marketplace\Helper\Data           $mpHelper
     * @param \Webkul\Marketplace\Model\SellerFactory   $seller
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Webkul\Marketplace\Helper\Data $mpHelper,
        \Webkul\Marketplace\Model\SellerFactory $seller
    ) {
        parent::__construct($context);
        $this->mpHelper = $mpHelper;
        $this->seller = $seller;
    }

    /**
     * get seller shop name
     *
     * @param string $shopUrl
     * @return string
     */
    public function getShopName($shopUrl)
    {
        $storeId = $this->mpHelper->getCurrentStoreId();
        $seller = $this->seller->create()
                        ->getCollection()
                        ->addFieldToFilter('shop_url', $shopUrl)
                        ->addFieldToFilter('store_id', $storeId)
                        ->setPageSize(1)->getFirstItem();
        return $seller->getShopTitle() ? $seller->getShopTitle() :
                                         $seller->getShopUrl();
    }
}
