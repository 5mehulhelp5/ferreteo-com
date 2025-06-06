<?php
/**
 * Magedelight
 * Copyright (C) 2019 Magedelight <info@magedelight.com>
 *
 * @category  Magedelight
 * @package   Magedelight_SMSProfile
 * @copyright Copyright (c) 2019 Mage Delight (http://www.magedelight.com/)
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author    Magedelight <info@magedelight.com>
 */
 
namespace Magedelight\SMSProfile\Controller\OTP;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magedelight\SMSProfile\Helper\Data as HelperData;
use Magedelight\SMSProfile\Model\SMSProfileOtpFactory;
use Magedelight\SMSProfile\Model\ResourceModel\SMSProfileOtp\CollectionFactory;
use Magento\Framework\Controller\Result\JsonFactory as ResultJsonFactory;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class Verify extends Action
{
    /**  @var HelperData */
    private $datahelper;

    /**
     * sms profile otp  factory
     *
     * @var SMSProfileOtpFactory
     */
    private $smsProfileOtp;

    /** @var ResultJsonFactory */
    private $resultJsonFactory;

     /**
      * @var CollectionFactory
      */
    private $collection;

    /**  @var TimezoneInterface */
    private $timezone;

    /**
     * @param Context $context
     * @param SMSProfileOtpFactory $smsProfileOtp
     * @param ResultJsonFactory $resultJsonFactory
     * @param CollectionFactory $collectionFactory
     * @param HelperData $dataHelper
     */

    public function __construct(
        Context $context,
        SMSProfileOtpFactory $smsProfileOtp,
        ResultJsonFactory $resultJsonFactory,
        CollectionFactory $collection,
        TimezoneInterface $timezone,
        HelperData $dataHelper
    ) {
        parent::__construct($context);
        $this->datahelper = $dataHelper;
        $this->smsProfileOtp = $smsProfileOtp;
        $this->collection = $collection;
        $this->timezone = $timezone;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute()
    {
        $_data =  $this->getRequest()->getPostValue();
        $toNumber = $_data['mobile'];
        $otp = $_data['otp'];
        $minutes = $this->datahelper->getSmsProfileOTPExpiry();
        $now = $this->timezone->date(null, null, false)->format('Y-m-d H:i:s');
        $now2 = $this->timezone->date(null, null, false)->modify('-' . $minutes . 'minute')->format('Y-m-d H:i:s');
        $result = $this->resultJsonFactory->create();
        $smsProfileOtp = $this->collection->create();
        $smsProfileOtp->addFieldToFilter('customer_mobile', $toNumber);
        $smsProfileOtp->addFieldToFilter('created_at', ['from' => $now2, 'to' => $now]);
        $smsProfileOtp->addFieldToFilter('created_at', ['gteq' => $now2, 'lteq' => $now]);
        $smsProfileOtp->getLastItem();
        if ($smsProfileOtp->getSize()) {
            $data = $smsProfileOtp->getLastItem();
            if ($data->getOtpCode() == $otp) {
                $data->delete();
                $result->setData(['message' => __('Verified')]);
            } else {
                $result->setData(['message' => __('Not Verified')]);
            }
        } else {
            $result->setData(['message' => __('OTP is expired')]);
        }
        return $result;
    }
}
