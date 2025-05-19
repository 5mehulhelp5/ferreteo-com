<?php
/**
 * @category   Webkul
 * @package    Webkul_MpSellerBadge
 * @author     Webkul Software Private Limited
 * @copyright  Copyright (c)  Webkul Software Private Limited (https://webkul.com)
 * @license    https://store.webkul.com/license.html
 */
namespace Webkul\MpSellerBadge\Controller\Adminhtml\Badges;

use Magento\Backend\App\Action;
use Webkul\MpSellerBadge\Model\Badge;
use Magento\Ui\Component\MassAction\Filter;

class MassEnable extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $fileUploaderFactory;

    /**
     * object of badge model
     * @var Badge
     */
    protected $badge;

    /**
     * filter object of Filter
     * @var Filter
     */
    protected $filter;

    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $connection;

    /**
     * @var Magento\Framework\App\ResourceConnection
     */
    protected $resource;

    /**
     * @param Action\Context                                   $context
     * @param Filter                                           $filter
     * @param Badge                                            $badge
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     */
    public function __construct(
        Action\Context $context,
        Badge $badge,
        Filter $filter,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\App\ResourceConnection $resource
    ) {
        $this->filter = $filter;
        $this->badge = $badge;
        $this->fileUploaderFactory = $fileUploaderFactory;
        $this->connection = $resource->getConnection();
        $this->resource = $resource;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_MpSellerBadge::m_badge');
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $imagesModel = $this->badge;
        $model = $this->filter;
        $collection = $model->getCollection($imagesModel->getCollection());
        
        $ids = [];
        foreach ($collection as $image) {
            $ids[] = $image->getId();
        }

        $update = ['status' => Badge::STATUS_ENABLED];
        $where = ['entity_id IN (?)' => $ids];
        try {
            $this->connection->beginTransaction();
            $this->connection->update($this->resource->getTableName(Badge::TABLE_NAME), $update, $where);
            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollBack();
        }

        $this->messageManager->addSuccess(__('Badge(s) enabled successfully.'));
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }

    public function setStatus($model, $status)
    {
        $model->setStatus($status)->setId($model->getId())->save();
    }
}
