<?php

namespace Magecomp\Cityandregionmanager\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const CONFIG_CUSTOM_IS_ENABLED = 'region_manager_config/general_config_group/enabled_extension_yes_no';
    const ZIPCODE_OPTIONAL = 'region_manager_config/general_config_group/zipcode_optional';
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\View\Asset\Repository $assetRepository,
        \Magecomp\Cityandregionmanager\Model\ResourceModel\States\CollectionFactory $statesCollection
    ) {
        parent::__construct($context);
        $this->_statesCollection    = $statesCollection;
        $this->storeManager = $storeManager;
        $this->assetRepository = $assetRepository;
    }

    public function isModuleEnabled()
    {
        $storeScope = ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue(self::CONFIG_CUSTOM_IS_ENABLED, $storeScope);
    }
     public function isZipcodeEnabled()
    {
        $storeScope = ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue(self::ZIPCODE_OPTIONAL, $storeScope);
    }
    public function getSates(){
        return $collection = $this->_statesCollection->create()
            ->setOrder('states_name','ASC')
            ->getData();
    }
    public function getTemplate()
    {
        if ($this->isModuleEnabled()) {
            $template = 'Magecomp_Cityandregionmanager::address/edit.phtml';
        } else {
            $template = 'Magento_Customer::address/edit.phtml';
        }
        return $template;
    }
}
