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
use Webkul\MpSellerBadge\Model\Sellerbadge;

class MassAssignBadge extends Action
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
     * @var Sellerbadge
     */
    protected $sellerBadge;

    /**
     * @param Context                        $context
     * @param Seller                         $sellerModel
     * @param Filter                         $filter
     * @param Sellerbadge                    $sellerBadge
     * @param SellerbadgeRepositoryInterface $sellerBadgeFactory
     * @param PageFactory                    $resultPageFactory
     */
    public function __construct(
        Context $context,
        Seller $sellerModel,
        Filter $filter,
        Sellerbadge $sellerBadge,
        SellerbadgeRepositoryInterface $sellerBadgeFactory,
        PageFactory $resultPageFactory
    ) {
        $this->sellerBadge = $sellerBadge;
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
        die("ddd");
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
                $checkStatus = 2;
            } else {
                $data = ['badge_id'=>$badgeId,'seller_id'=>$id,'created_at'=>time()];
                $this->assignBadgeToSeller($data);
                $checkStatus = 1;
            }
        }
        if ($checkStatus == 0) {
            $this->messageManager->addSuccess(__('Something went wrong.'));
        } elseif ($checkStatus == 1) {
            $this->messageManager->addSuccess(__('Badge(s) assigned successfully.'));
        } else {
            $this->messageManager->addSuccess(__('Badge(s) already assigned'));
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

    public function assignBadgeToSeller($data)
    {
        $badgeModel = $this->sellerBadge->setData($data);
        $badgeModel->save();
    }
}
