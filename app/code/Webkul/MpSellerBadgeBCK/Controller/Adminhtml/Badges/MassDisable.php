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

class MassDisable extends \Magento\Backend\App\Action
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
     * @param Action\Context                                   $context
     * @param Badge                                            $badge
     * @param Filter                                           $filter
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     */
    public function __construct(
        Action\Context $context,
        Badge $badge,
        Filter $filter,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
    ) {
        $this->filter = $filter;
        $this->badge = $badge;
        $this->fileUploaderFactory = $fileUploaderFactory;
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
        foreach ($collection as $image) {
            $this->setStatus($image, 0);
        }
        $this->messageManager->addSuccess(__('Badge(s) disabled successfully.'));
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
    
    public function setStatus($model, $status)
    {
        $model->setStatus($status)->setId($model->getId())->save();
    }
}
