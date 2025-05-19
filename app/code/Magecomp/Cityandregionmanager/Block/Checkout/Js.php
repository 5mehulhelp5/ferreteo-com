<?php

namespace Magecomp\Cityandregionmanager\Block\Checkout;

use Magento\Framework\View\Element\Template;
use Magecomp\Cityandregionmanager\Model\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
class Js extends \Magento\Framework\View\Element\Template
{
    protected $_config;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Template\Context $context,
        Config $config,
        array $data = []
    )
    {
        $this->_scopeConfig = $scopeConfig;
        $this->_config = $config;
        parent::__construct($context, $data);
    }

    public function enableModule()
    {
        return $this->_config->getEnableExtensionYesNo() == 1 ? true : false;
    }

    public function enableButtons()
    {
        return $this->_config->getEnableButtonsYesNo() == 1 ? true : false;
    }
    public function getZipcodeOptionalYesNo()
{
        return $this->_scopeConfig->getValue("region_manager_config/general_config_group/zipcode_optional");
        
}

}