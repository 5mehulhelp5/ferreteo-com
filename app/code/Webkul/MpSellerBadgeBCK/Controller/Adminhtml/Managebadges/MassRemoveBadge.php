<?php
/**
 * @category   Webkul
 * @package    Webkul_MpSellerBadge
 * @author     Webkul Software Private Limited
 * @copyright  Copyright (c)  Webkul Software Private Limited (https://webkul.com)
 * @license    https://store.webkul.com/license.html
 */
namespace Webkul\MpSellerBadge\Controller\Adminhtml\Managebadges;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Webkul\Marketplace\Model\Seller;
use Magento\Ui\Component\MassAction\Filter;
use Webkul\MpSellerBadge\Api\SellerbadgeRepositoryInterface;

class MassRemoveBadge extends Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * model of seller
     * @var Seller
     */
    protected $sellerModel;

    /**
     * filter object of Filter
     * @var Filter
     */
    protected $filter;

    /**
     * object of SellebadgeFactory
     * @var SellerbadgeFactory
     */
    protected $sellerBadgeFactory;

    /**
     * @param Context                        $context
     * @param Seller                         $sellerModel
     * @param Filter                         $filter
     * @param SellerbadgeRepositoryInterface $sellerBadgeFactory
     * @param PageFactory                    $resultPageFactory
     */
    public function __construct(
        Context $context,
        Seller $sellerModel,
        Filter $filter,
        SellerbadgeRepositoryInterface $sellerBadgeFactory,
        PageFactory $resultPageFactory
    ) {
        $this->sellerBadgeFactory = $sellerBadgeFactory;
        $this->filter = $filter;
        $this->sellerModel = $sellerModel;
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $sellerIds = [];
        $checkStatus = 0;
        $badgeId = $this->getRequest()->getParam('entity_id');
        $mpModel = $this->sellerModel;
        $model = $this->filter;
        $collection = $model->getCollection($mpModel->getCollection());
        foreach ($collection as $value) {
            $sellerIds[] = $value->getSellerId();
        }
        foreach ($sellerIds as $id) {
            $badgeModelCollection = $this->sellerBadgeFactory->getSellerBadgeCollection($id, $badgeId);
            if ($badgeModelCollection->getSize()) {
                $badgeModelCollection->walk('delete');
                $checkStatus = 2;
            } else {
                $checkStatus = 1;
            }
        }
        if ($checkStatus == 0) {
            $this->messageManager->addSuccess(__('Something went wrong.'));
        } elseif ($checkStatus == 1) {
            $this->messageManager->addSuccess(__('Badge(s) not assigned.'));
        } else {
            $this->messageManager->addSuccess(__('Badge(s) removed from the seller.'));
        }
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Check for is allowed.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_MpSellerBadge::seller_badge');
    }
}
