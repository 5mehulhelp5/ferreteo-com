<?php

/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Ibnab\BankInfo\Model;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Bank Transfer payment method model
 *
 * @method \Magento\Quote\Api\Data\PaymentMethodExtensionInterface getExtensionAttributes()
 */
class Banktransfer extends \Magento\OfflinePayments\Model\Banktransfer {

    const XML_PATH_SYSTEM_CONFIG = "payment/banktransfer/";

    public function getAllbank() {
        $serializer = ObjectManager::getInstance()->get(Json::class);
        $resultConfigBank = trim($this->_scopeConfig->getValue(self::XML_PATH_SYSTEM_CONFIG . 'allbanksystem', ScopeConfigInterface::SCOPE_TYPE_DEFAULT));
        $bankResult = [];

        if ($resultConfigBank != '' ) {
            $allBank = $serializer->unserialize($resultConfigBank);
            $bankResult = [];
            if (is_array($allBank)) {
                foreach ($allBank as $bank) {
                    $bankResult[$bank['bank']] = array(
                        'bank' => $bank['bank'],
                        'description' => $bank['description'],
                        'additional1' => $bank['additional1'],
                        'additional2' => $bank['additional2']
                    );
                }
            }
        }

        return $bankResult;
    }

    public function getActivebank() {
        return trim($this->getConfigData('activebank'));
    }

    public function getActivebankowner() {
        return trim($this->getConfigData('activebankowner'));
    }

    function is_serialized($value, &$result = null) {
        if (!is_string($value)) {
            return false;
        }

        if ($value === 'b:0;') {
            $result = false;
            return true;
        }
        $length = strlen($value);
        $end = '';
        if(isset($value[0])){
        switch ($value[0]) {
            case 's':
                if ($value[$length - 2] !== '"') {
                    return false;
                }
            case 'b':
            case 'i':
            case 'd':
                // This looks odd but it is quicker than isset()ing
                $end .= ';';
            case 'a':
            case 'O':
                $end .= '}';
                if ($value[1] !== ':') {
                    return false;
                }
                switch ($value[2]) {
                    case 0:
                    case 1:
                    case 2:
                    case 3:
                    case 4:
                    case 5:
                    case 6:
                    case 7:
                    case 8:
                    case 9:
                        break;
                    default:
                        return false;
                }
            case 'N':
                $end .= ';';
                if ($value[$length - 1] !== $end[0]) {
                    return false;
                }
                break;
            default:
                return false;
        }
        }
        if (($result = @unserialize($value)) === false) {
            $result = null;
            return false;
        }
        return true;
    }

}
