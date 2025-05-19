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
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;
use Webkul\MpSellerBadge\Api\BadgeRepositoryInterface;
use Webkul\MpSellerBadge\Api\SellerbadgeRepositoryInterface;
use Magento\Framework\Registry;
use Webkul\MpSellerBadge\Model\Badge;

class Edit extends Action
{
    /**
     * badge repository
     * @var badgeRepository
     */
    protected $badgeRepository;

    /**
     * seller badge repository
     * @var SellerbadgeRepositoryInterface
     */
    protected $sellerBadgeRepository;

    /**
     * backend session
     * @var Session
     */
    protected $session;

    /**
     * registry
     * @var Registry
     */
    protected $registry;

    /**
     * object of badge model
     * @var Badge
     */
    protected $badge;

    /**
     * @param Context                        $context
     * @param Registry                       $registry
     * @param Badge                          $badge
     * @param BadgeRepositoryInterface       $badgeRepository
     * @param SellerbadgeRepositoryInterface $sellerBadgeRepository
     * @param PageFactory                    $resultPageFactory
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Badge $badge,
        BadgeRepositoryInterface $badgeRepository,
        SellerbadgeRepositoryInterface $sellerBadgeRepository,
        PageFactory $resultPageFactory
    ) {
    
        $this->badge = $badge;
        $this->registry = $registry;
        $this->session = $context->getSession();
        $this->badgeRepository = $badgeRepository;
        $this->sellerBadgeRepository = $sellerBadgeRepository;
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
    }
    
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $badgeModel = $this->badge;
        if ($this->getRequest()->getParam('id')) {
            $badgeModel->load($this->getRequest()->getParam('id'));
        }
        $data = $this->session->getFormData(true);
        if (!empty($data)) {
            $badgeModel->setData($data);
        }
        $this->registry->register('mpsellerbadge_badges', $badgeModel);
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend(__('Badges'));
        $resultPage->getConfig()->getTitle()->prepend(
            $badgeModel->getId() ? __('Update Badge ').$badgeModel->getBadgeName() : __('New Badge')
        );
        $resultPage->addBreadcrumb(__('Manage Badges'), __('Manage Badges'));
        $resultPage->addContent(
            $resultPage->getLayout()->createBlock(
                \Webkul\MpSellerBadge\Block\Adminhtml\Badges\Edit::class
            )
        );

        return $resultPage;
    }

    /**
     * Check for is allowed
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_MpSellerBadge::m_badge');
    }
}
