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
use Webkul\MpSellerBadge\Api\BadgeRepositoryInterface;

class Image extends \Magento\Ui\Component\Listing\Columns\Column
{
    const NAME = 'image';

    const ALT_FIELD = 'name';
    /**
     * object of store manger class
     * @var storemanager
     */
    protected $storeManager;
    /**
     * object of BadgeRepositoryInterface
     * @var badgeRepository
     */
    protected $badgeRepository;
    /**
     * @param ContextInterface      $context
     * @param UiComponentFactory    $uiComponentFactory
     * @param StoreManagerInterface $storemanager
     * @param array                 $components
     * @param array                 $data
     */
    public function __construct(
        ContextInterface $context,
        BadgeRepositoryInterface $badgeRepository,
        UiComponentFactory $uiComponentFactory,
        StoreManagerInterface $storemanager,
        array $components = [],
        array $data = []
    ) {
        $this->badgeRepository = $badgeRepository;
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->storeManager = $storemanager;
        $this->editUrl ="";
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
                $image = $this->badgeRepository->getBadgeCollectionById($item['entity_id']);
                $imageName = $image->getBadgeImageUrl();
                $imageTitle = $image->getBadgeName();
                $item[$fieldName . '_src'] = $mediaDirectory.$imageName;
                $item[$fieldName . '_alt'] = $this->getAlt($item) ?: $imageTitle;
                $item[$fieldName . '_orig_src'] = $mediaDirectory.$imageName;
            }
        }
        return $dataSource;
    }
}
