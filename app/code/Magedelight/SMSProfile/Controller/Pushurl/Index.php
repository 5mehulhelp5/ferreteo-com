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
 
namespace Magedelight\SMSProfile\Controller\Pushurl;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magedelight\SMSProfile\Model\SMSProfileLogFactory;
use Magedelight\SMSProfile\Helper\Data as HelperData;
use Magedelight\SMSProfile\Model\SMSProfileService;
use Magento\Framework\Json\Helper\Data as JsonHelper;

class Index extends Action
{
    
    /** @var SMSProfileService */
    private $smsService;

    /** @var SMSProfileLogFactory */
    private $smslog;

     /**  @var HelperData */
    private $datahelper;

    /**
     * @var JsonHelper
     */
    private $jsonHelper;

    /**
     * @param Context $context
     * @param SMSProfileLogFactory $smslog
     * @param SMSProfileService $smsService
     * @param HelperData $dataHelper
     * @param JsonHelper $jsonHelper
     */
    public function __construct(
        Context $context,
        HelperData $dataHelper,
        SMSProfileService $smsService,
        JsonHelper $jsonHelper,
        SMSProfileLogFactory $smslog
    ) {
        parent::__construct($context);
        $this->smslog = $smslog;
        $this->smsService = $smsService;
        $this->datahelper = $dataHelper;
        $this->jsonHelper = $jsonHelper;
    }

     /**
      * SmsLog Update Staus by webhook.
      *
      * @return RedirectFactory
      */
    public function execute()
    {
        if ($this->datahelper->getSmsProfileApiGateWay() == "Other") {
            $this->updateStatus();
        }
    }

    public function updateStatus()
    {
        $other_url =$this->datahelper->getSmsProfileApiUrl();
        if (strpos($other_url, 'msg91') !== false) {
            $this->updateStatusForMsg91();
        }
    }

    public function updateStatusForMsg91()
    {
        $request = $this->getRequest()->getParams();
        //$jsonData = $this->jsonHelper->jsonDecode($request,true);
    
        foreach ($request as $key => $value) {
             // request id
            $requestID = $value['requestId'];
            $userId = $value['userId'];
            $senderId = $value['senderId'];
            $smslog = $this->smslog->create();
            foreach ($value['report'] as $key1 => $value1) {
                //detail description of report
                $desc = $value1['desc'];

                // status of each number
                $status = $value1['status'];

                $_sms = $smslog->getCollection();
                $_sms->addFieldToFilter('s_id', $requestID);

                foreach ($_sms as $sms) {

                    $additionalData['toNumber'] = $sms->getRecipientPhone();
                    $additionalData["transactionType"] =$sms->getTransactionType();
         
                    if ($desc =='Failed' || $desc =='Rejected') {
                        $sms->setIsError(1);
                        $error = __('Delivery Failed due to unknown reason.');
                        $sms->setErrorMessage($error);
                        $smslog->sendFailureMail($additionalData, $error);

                    }
                    $sms->setStatus($desc);
                    $sms->save();
                }
            }
        }
    }
}
