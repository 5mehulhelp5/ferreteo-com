<?php
/**
 * @category   Webkul
 * @package    Webkul_MpSellerBadge
 * @author     Webkul Software Private Limited
 * @copyright  Copyright (c)  Webkul Software Private Limited (https://webkul.com)
 * @license    https://store.webkul.com/license.html
 */
namespace Webkul\MpSellerBadge\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;
use Webkul\MpSellerBadge\Api\SellerbadgeRepositoryInterface;
use Webkul\MpSellerBadge\Api\BadgeRepositoryInterface;

class Sellerbadge extends \Magento\Ui\Component\Listing\Columns\Column
{
    const NAME = 'badge';

    const ALT_FIELD = 'name';

    /**
     * object of store manger class
     * @var storemanager
     */
    protected $storeManager;

    /**
     * object of SellerbadgeRepositoryInterface
     * @var badgeRepository
     */
    protected $sellerBadgeRepository;

    /**
     * object of BadgeRepositoryInterface
     * @var badgeRepository
     */
    protected $badgeRepository;

    /**
     * @param ContextInterface               $context
     * @param SellerbadgeRepositoryInterface $sellerBadgeRepository
     * @param BadgeRepositoryInterface       $badgeRepository
     * @param UiComponentFactory             $uiComponentFactory
     * @param StoreManagerInterface          $storemanager
     * @param array                          $components
     * @param array                          $data
     */
    public function __construct(
        ContextInterface $context,
        SellerbadgeRepositoryInterface $sellerBadgeRepository,
        BadgeRepositoryInterface $badgeRepository,
        UiComponentFactory $uiComponentFactory,
        StoreManagerInterface $storemanager,
        array $components = [],
        array $data = []
    ) {
        $this->badgeRepository = $badgeRepository;
        $this->sellerBadgeRepository = $sellerBadgeRepository;
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->storeManager = $storemanager;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        $mediaDirectory = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $badgeArray=[];

                $sellerBadgeCollection = $this->sellerBadgeRepository->getSellerBadgeCollectionBySellerId(
                    $item['seller_id']
                );
                if ($sellerBadgeCollection->getSize()) {
                    foreach ($sellerBadgeCollection as $key => $badge) {
                        $badgeArray[]=$badge->getBadgeId();
                    }
                    
                    $imagesContainer='';
                    $badgeCollection = $this->badgeRepository->getExistingBadges($badgeArray);
                    foreach ($badgeCollection as $badgeInfo) {
                        $badgeName = $badgeInfo->getBadgeImageUrl();
                        $imageTitle = $badgeInfo->getBadgeName();
                        $imageUrl = $mediaDirectory.$badgeName;
                        $imagesContainer = $imagesContainer."<img title='".$imageTitle."' 
                        src=". $imageUrl ." width='50px' height='50px' style='display:inline-block;margin:2px'/>"
                        ."<br/>";
                    }
                } else {
                    $imagesContainer = __('No Badge Assigned');
                }
                $item[$fieldName]=$imagesContainer;
            }
        }
        return $dataSource;
    }
}
