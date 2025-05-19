<?php
namespace Magecomp\Cityandregionmanager\Model;


use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    protected $_scopeConfig;

    protected $_storeId = null;

    protected $_storeManager;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    )
    {
        $this->_scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
    }

    public function getStoreId()
    {
        if ($this->_storeId === null) {
            $this->_storeId = (int)$this->_storeManager->getStore()->getId();
        }
        return $this->_storeId;
    }

    public function getEnableExtensionYesNo()
    {
        return $this->_scopeConfig->getValue("region_manager_config/general_config_group/enabled_extension_yes_no", ScopeInterface::SCOPE_STORE, (int)$this->getStoreId());
    }

    public function getEnableButtonsYesNo()
    {
        return $this->_scopeConfig->getValue("region_manager_config/general_config_group/enable_buttons_yes_no", ScopeInterface::SCOPE_STORE, (int)$this->getStoreId());
    }

}