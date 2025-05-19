<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Smartwave\Porto\Helper;

class Customer extends \Magento\Framework\App\Helper\AbstractHelper
{

    protected $_objectManager;
    private $_customerSession;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->_objectManager = $objectManager;
        $this->_customerSession = $customerSession;

        parent::__construct($context);
    }

    public function isLoggedIn() {
        return $this->_customerSession->isLoggedIn();
    }

    public function getName() {
        if ($this->isLoggedIn()) {
            return $this->_customerSession->getCustomer()->getName();
        }
        return '';
    }

    public function getNameInitial() {
        if ($this->isLoggedIn()) {
            $name = $this->_customerSession->getCustomer()->getName();
            preg_match_all("/[A-Z]/", ucwords(strtolower($name)), $initials);
            // $initial = implode('', $initials[0]);
            $initial = $initials[0][0].$initials[0][1];
            return $initial;
        }
        return '';
    }

}
