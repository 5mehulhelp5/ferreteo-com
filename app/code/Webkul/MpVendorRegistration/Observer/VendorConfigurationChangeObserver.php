<?php
namespace Webkul\MpVendorRegistration\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface as Logger;
use Webkul\MpVendorRegistration\Model\VendorRegistrationAttributeFactory;
use Webkul\MpVendorRegistration\Model\VendorRegistrationGroupFactory;

class VendorConfigurationChangeObserver implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var groupCollectionFactory
     */
    protected $groupCollectionFactory;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var attributeCollectionFactory
     */
    protected $attributeCollectionFactory;

    /**
     * @var \Magento\Framework\App\Config\Storage\WriterInterface
     */
    protected $configWriter;

    /**
     * Undocumented function
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param VendorRegistrationGroupFactory $groupCollectionFactory
     * @param VendorRegistrationAttributeFactory $attributeCollectionFactory
     * @param \Magento\Framework\App\Config\Storage\WriterInterface $configWriter
     * @param Logger $logger
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        VendorRegistrationGroupFactory $groupCollectionFactory,
        VendorRegistrationAttributeFactory $attributeCollectionFactory,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        Logger $logger
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->groupCollectionFactory = $groupCollectionFactory;
        $this->attributeCollectionFactory = $attributeCollectionFactory;
        $this->logger = $logger;
        $this->configWriter = $configWriter;
    }
    /**
     * admin configuration save after event handler.
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(EventObserver $observer)
    {
        try {
            $path = 'vendor_registration_section/vendor_registration/show_address';
            $canShowGroup = $this->scopeConfig->getValue(
                $path,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $this->storeManager->getStore()
            );
            $groupData = $this->groupCollectionFactory->create()->getCollection()
                ->addFieldToFilter('group_code', ['eq' => 'addressinfo']);
            foreach ($groupData as $data) {
                $data->setShowInFrontend($canShowGroup);
                $data->setGroupStatus($canShowGroup);
                $data->save();
            }

            $pathPayment = 'vendor_registration_section/vendor_registration/show_payment';
            $canShowPayment = $this->scopeConfig->getValue(
                $pathPayment,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $this->storeManager->getStore()
            );
            $groupData = $this->groupCollectionFactory->create()->getCollection()
                ->addFieldToFilter('group_code', ['eq' => 'paymentinfo']);
            foreach ($groupData as $data) {
                $data->setShowInFrontend($canShowPayment);
                $data->setGroupStatus($canShowPayment);
                $data->save();
            }

            $pathSocialGroup = 'vendor_registration_section/vendor_registration/show_socialgroup';
            $canShowSocialGroup = $this->scopeConfig->getValue(
                $pathSocialGroup,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $this->storeManager->getStore()
            );
            $groupData = $this->groupCollectionFactory->create()->getCollection()
                ->addFieldToFilter('group_code', ['eq' => 'socialgroup']);
            foreach ($groupData as $data) {
                $data->setShowInFrontend($canShowSocialGroup);
                $data->setGroupStatus($canShowSocialGroup);
                $data->save();
            }
            if ($canShowSocialGroup) {
                $pathSocialFieldsEnable = 'vendor_registration_section/vendor_registration/show_socialfields';
                $pathSocialFieldsRequired = 'vendor_registration_section/vendor_registration/require_socialfields';
                $showSocialItems = $this->scopeConfig->getValue(
                    $pathSocialFieldsEnable,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $this->storeManager->getStore()
                );
                $requireSocialItems = $this->scopeConfig->getValue(
                    $pathSocialFieldsRequired,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $this->storeManager->getStore()
                );
                $showSocialItems = explode(',', $showSocialItems);
                $requireSocialItems = explode(',', $requireSocialItems);
                $attributeData = $this->attributeCollectionFactory->create()
                    ->getCollection()
                    ->addFieldToFilter('attribute_id', ['eq' => 0])
                    ->addFieldToFilter('attribute_code', ['like' => '%_id%']);
                foreach ($attributeData as $data) {
                    if (in_array($data->getAttributeCode(), $showSocialItems)) {
                        $data->setIsRequired(0);
                        if (in_array($data->getAttributeCode(), $requireSocialItems)) {
                            $data->setIsRequired(1);
                        }
                        $data->setAttributeStatus(1);
                        $data->setShowInFrontend(1);
                        $data->save();
                    } else {
                        $data->setAttributeStatus(0);
                        $data->setIsRequired(0);
                        $data->setShowInFrontend(0);
                        $data->save();
                    }
                }
            }
            $this->configWriter->save('marketplace/landingpage_settings/allow_seller_registration_block', 0);
        } catch (\Exception $e) {
            $this->logger->debug("VendorConfigurationChangeObserver exception : " . $e->getMessage());
        }
    }
}
