<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_MpVendorRegistration
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\MpVendorRegistration\Observer;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Event\ObserverInterface;
use \Magento\Customer\Model\ResourceModel\Attribute\CollectionFactory;

class VendorRegistrationSuccessObserver implements ObserverInterface
{

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $attributeCollection;

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $eavEntity;

    protected $logger;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;
    /**
     * @var CustomerInterfaceFactory
     */
    protected $customerDataFactory;
    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;
    /**
     * @var \Magento\Customer\Model\Customer\Mapper
     */
    protected $customerMapper;

    /**
     * Undocumented function
     *
     * @param \Magento\Framework\Logger\Monolog $logger
     * @param CollectionFactory $attributeCollection
     * @param CustomerInterfaceFactory $customerDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Magento\Eav\Model\Entity $eavEntity
     * @param \Magento\Customer\Model\Customer\Mapper $customerMapper
     * @param CustomerRepositoryInterface $customerRepository
     * @param \Webkul\Marketplace\Model\SellerFactory $sellerCollection
     * @param \Webkul\Marketplace\Helper\Data $mpHelper
     */
    public function __construct(
        \Magento\Framework\Logger\Monolog $logger,
        CollectionFactory $attributeCollection,
        CustomerInterfaceFactory $customerDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Magento\Eav\Model\Entity $eavEntity,
        \Magento\Customer\Model\Customer\Mapper $customerMapper,
        CustomerRepositoryInterface $customerRepository,
        \Webkul\Marketplace\Model\SellerFactory $sellerCollection,
        \Webkul\Marketplace\Helper\Data $mpHelper
    ) {
        $this->customerRepository = $customerRepository;
        $this->customerDataFactory = $customerDataFactory;
        $this->customerMapper = $customerMapper;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->logger = $logger;
        $this->attributeCollection = $attributeCollection;
        $this->eavEntity = $eavEntity;
        $this->sellerCollection = $sellerCollection;
        $this->mpHelper = $mpHelper;
    }

    /**
     * customer register event handler.
     * save vendor attributes
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $data = $observer['account_controller'];
            $paramData = $data->getRequest();
            $customer = $observer->getCustomer();
            $customerId = $customer->getId();
            $typeId = $this->eavEntity->setType('customer')->getTypeId();
            $collection = $this->attributeCollection->create()
                ->setEntityTypeFilter($typeId)
                ->addVisibleFilter()
                ->setOrder('sort_order', 'ASC');
            $customData = $paramData->getPostValue();

            // save payment info
            if (!empty($customData['is_seller']) &&
                !empty($customData['payment_source']) &&
                !empty($customData['profileurl']) &&
                $customData['is_seller'] == 1
            ) {
                $profileurlcount = $this->sellerCollection->create()->getCollection();

                $profileurlcount->addFieldToFilter(
                    'shop_url',
                    $customData['profileurl']
                );
                if ($profileurlcount->getSize()) {
                    foreach ($profileurlcount as $profileData) {
                        if (isset($customData['twitter_id']) && $customData['twitter_id'] != '') {
                            $profileData->setTwitterId($customData['twitter_id']);
                            $profileData->setInstagramActive(1);
                        }
                        if (isset($customData['facebook_id']) && $customData['facebook_id'] != '') {
                            $profileData->setFacebookId($customData['facebook_id']);
                            $profileData->setInstagramActive(1);
                        }
                        if (isset($customData['gplus_id']) && $customData['gplus_id'] != '') {
                            $profileData->setGplusId($customData['gplus_id']);
                            $profileData->setInstagramActive(1);
                        }
                        if (isset($customData['youtube_id']) && $customData['youtube_id'] != '') {
                            $profileData->setYoutubeId($customData['youtube_id']);
                            $profileData->setInstagramActive(1);
                        }
                        if (isset($customData['vimeo_id']) && $customData['vimeo_id'] != '') {
                            $profileData->setVimeoId($customData['vimeo_id']);
                            $profileData->setInstagramActive(1);
                        }
                        if (isset($customData['instagram_id']) && $customData['instagram_id'] != '') {
                            $profileData->setInstagramId($customData['instagram_id']);
                            $profileData->setInstagramActive(1);
                        }
                        if (isset($customData['pinterest_id']) && $customData['pinterest_id'] != '') {
                            $profileData->setPinterestId($customData['pinterest_id']);
                            $profileData->setPinterestActive(1);
                        }
                        $profileData->setPaymentSource($customData['payment_source']);
                        $profileData->save();
                    }
                }
            }

            // save custom attributes
            $customDataNew = [];
            foreach ($customData as $key => $value) {
                if (strpos($key, 'wkmpvr') !== false) {
                    $customDataNew[$key] = $value;
                }
            }
            $savedCustomerData = $this->customerRepository->getById($customerId);
            $customer = $this->customerDataFactory->create();
            $customDataNew = array_merge(
                $customDataNew,
                $this->customerMapper->toFlatArray($savedCustomerData)
            );
            $customDataNew['id'] = $customerId;
            if (!isset($customDataNew['is_vendor_group'])) {
                $customDataNew['is_vendor_group'] = 0;
            }
            $this->dataObjectHelper->populateWithArray(
                $customer,
                $customDataNew,
                \Magento\Customer\Api\Data\CustomerInterface::class
            );
            $this->customerRepository->save($customer);
        } catch (\Exception $e) {
            $this->logger->debug("Configuration observer exception : " . $e->getMessage());
        }
    }
}
