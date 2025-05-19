<?php
namespace Ibnab\BankInfo\Block\Form;

/**
 * Block for Bank Transfer payment method form
 */
class Banktransfer extends \Magento\OfflinePayments\Block\Form\Banktransfer
{
    /**
     * Bank transfer template
     *
     * @var string
     */
    protected $_template = 'Ibnab_BankInfo::form/banktransfer.phtml';
    
    protected $_allbank;
    /**
     * Get instructions text from config
     *
     * @return null|string
     */
    public function getAllbank()
    {
        if ($this->_allbank === null) {
            /** @var \Magento\Payment\Model\Method\AbstractMethod $method */
            $method = $this->getMethod();
            $this->_allbank = $method->getConfigData('allbank');
        }
        return $this->_allbank;
    }
}
