<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_AdminLoginAsVendor
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\AdminLoginAsVendor\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Security\Model\AdminSessionsManager;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class AdminLoginAsVendor Actions.
 */
class ManageAction extends Column
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var AdminSessionsManager
     */
    protected $adminSessionsManager;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var EncryptorInterface
     */
    protected $encryptor;

    /**
     * @var JsonHelper
     */
    protected $jsonHelper;

    /**
     * @param ScopeConfigInterface      $scopeConfig
     * @param ContextInterface      $context
     * @param UiComponentFactory    $uiComponentFactory
     * @param AdminSessionsManager  $adminSessionsManager
     * @param StoreManagerInterface $storeManager
     * @param EncryptorInterface    $encryptor
     * @param JsonHelper            $jsonHelper
     * @param array                 $components
     * @param array                 $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        AdminSessionsManager $adminSessionsManager,
        StoreManagerInterface $storeManager,
        EncryptorInterface $encryptor,
        JsonHelper $jsonHelper,
        array $components = [],
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->adminSessionsManager = $adminSessionsManager;
        $this->storeManager = $storeManager;
        $this->encryptor = $encryptor;
        $this->jsonHelper = $jsonHelper;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source.
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $websiteId = $item['website_id']['0'];
                $loginUrls = '';
                $allUrls = $this->getAllWebsiteBaseUrls($websiteId);
                $adminSessionId = $this->adminSessionsManager
                ->getCurrentSession()
                ->getSessionId();
                $isVendorFlag = 0;
                $urlEntityParamName = $this->getData('config/urlEntityParamName') ?: 'seller_id';
                if ($urlEntityParamName == 'seller_id') {
                    $isVendorFlag = 1;
                    $userId = $item['seller_id'];
                } else {
                    $userId = $item['entity_id'];
                }
                $userInfo = [$urlEntityParamName => $userId, 'asid' => $adminSessionId];
                $userInfo = $this->encryptor->encrypt($this->jsonHelper->jsonEncode($userInfo));
                if (!empty($allUrls)) {
                    foreach ($allUrls as $url => $value) {
                        $actionUrl = $url.'adminloginasvendor/account/login/?cif='.$userInfo;
                        if ($isVendorFlag) {
                            $loginUrls = $loginUrls.
                            "<a 
                                href='".$actionUrl."' 
                                target='_blank' 
                                title='".__('Login As Vendor')."'
                            >".__('Login As Vendor for %1', $value)."</a><br/>";
                        } else {
                            $loginUrls = $loginUrls.
                            "<a 
                                href='".$actionUrl."' 
                                target='_blank' 
                                title='".__('Login As Customer')."'
                            >".__('Login As Customer for %1', $value)."</a><br/>";
                        }
                    }
                } else {
                    $url = $this->storeManager->getStore()->getBaseUrl();
                    $actionUrl = $url.'adminloginasvendor/account/login/?cif='.$userInfo;
                    if ($isVendorFlag) {
                        $loginUrls = $loginUrls.
                        "<a 
                            href='".$actionUrl."' 
                            target='_blank' 
                            title='".__('Login As Vendor')."'
                        >".__('Login As Vendor')."</a>";
                    } else {
                        $loginUrls = $loginUrls.
                        "<a 
                            href='".$actionUrl."' 
                            target='_blank' 
                            title='".__('Login As Customer')."'
                        >".__('Login As Customer')."</a>";
                    }
                }
                $item[$this->getData('name')] = $loginUrls;
            }
        }

        return $dataSource;
    }

    /**
     * get website url by product id
     * @param  int $productId
     * @return string
     */
    public function getAllWebsiteBaseUrls($websiteId)
    {
        $customerScope=$this->scopeConfig->getValue('customer/account_share/scope');
        $allUrls = [];
        $websites = $this->storeManager->getWebsites();
        if (count($websites) > 1) {
            foreach ($websites as $website) {
                if ($customerScope==1) {
                    if ($website->getWebsiteId()==$websiteId) {
                        foreach ($website->getStores() as $store) {
                            $storeObj = $this->storeManager->getStore($store);
                            $allUrls[$storeObj->getBaseUrl()] = $website->getName();
                        }
                    }
                } else {
                    foreach ($website->getStores() as $store) {
                        $storeObj = $this->storeManager->getStore($store);
                        $allUrls[$storeObj->getBaseUrl()] = $website->getName();
                    }
                }
            }
        }
        return $allUrls;
    }
}
