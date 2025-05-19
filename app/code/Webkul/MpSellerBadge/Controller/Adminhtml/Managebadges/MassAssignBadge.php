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
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $connection;

    /**
     * @var Magento\Framework\App\ResourceConnection
     */
    protected $resource;

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
        PageFactory $resultPageFactory,
        \Magento\Framework\App\ResourceConnection $resource
    ) {
        $this->sellerBadge = $sellerBadge;
        $this->sellerBadgeFactory = $sellerBadgeFactory;
        $this->filter = $filter;
        $this->sellerModel = $sellerModel;
        $this->connection = $resource->getConnection();
        $this->resource = $resource;
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
        
        $badgeModelCollection = $this->sellerBadgeFactory->getSellerBadgeCollection($sellerIds, $badgeId);
        $sellerIds = array_flip($sellerIds);
        if ($badgeModelCollection->getSize()) {
            foreach ($badgeModelCollection as $badgeData) {
                unset($sellerIds[$badgeData->getSellerId()]);
            }
            $checkStatus = 2;
        }
        $sellerIds = array_flip($sellerIds);
        if (count($sellerIds)) {
            foreach ($sellerIds as $sellerId) {
                $data[] = [
                    'badge_id' => $badgeId,
                    'seller_id' => $sellerId,
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
            $this->insertMultiple('mpsellerbadge', $data);
            $checkStatus = 1;
        }

        if ($checkStatus == 0) {
            $this->messageManager->addErrorMessage(__('Something went wrong.'));
        } elseif ($checkStatus == 1) {
            $this->messageManager->addSuccess(__('Badge(s) assigned successfully.'));
        } else {
            $this->messageManager->addErrorMessage(__('Badge(s) already assigned'));
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

    public function insertMultiple($table, $data)
    {
        try {
            $tableName = $this->resource->getTableName($table);
            return $this->connection->insertMultiple($tableName, $data);
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
    }
}
