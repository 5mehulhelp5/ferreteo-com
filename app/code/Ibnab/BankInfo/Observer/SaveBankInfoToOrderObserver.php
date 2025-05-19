<?php

namespace Ibnab\BankInfo\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Ibnab\BankInfo\Model\Banktransfer;

class SaveBankInfoToOrderObserver implements ObserverInterface {

    protected $_inputParamsResolver;
    protected $_quoteRepository;
    protected $logger;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectmanager
     */
    public function __construct(\Magento\Webapi\Controller\Rest\InputParamsResolver $inputParamsResolver, \Magento\Quote\Model\QuoteRepository $quoteRepository, \Psr\Log\LoggerInterface $logger) {
        $this->_inputParamsResolver = $inputParamsResolver;
        $this->_quoteRepository = $quoteRepository;
        $this->logger = $logger;
    }

    /**
     * @param EventObserver $observer
     * @return $this
     */
    public function execute(EventObserver $observer) {
        if (!is_null($this->_inputParamsResolver)) {
            try {
                $inputParams = $this->_inputParamsResolver->resolve();
                foreach ($inputParams as $inputParam) {
                    if ($inputParam instanceof \Magento\Quote\Model\Quote\Payment) {
                        $paymentData = $inputParam->getData('additional_data');
                        $paymentOrder = $observer->getEvent()->getPayment();
                        $order = $paymentOrder->getOrder();
                        $quote = $this->_quoteRepository->get($order->getQuoteId());
                        $paymentQuote = $quote->getPayment();
                        $method = $paymentQuote->getMethodInstance()->getCode();
                        if ($method == Banktransfer::PAYMENT_METHOD_BANKTRANSFER_CODE) {

                            //echo get_class($order->getPayment());die();
                            //$order->setData('bank_name', $paymentQuote->getData('bank_name'));
                            //$order->setData('bank_owner', $paymentQuote->getData('bank_owner'));
                            //$this->logger->info($paymentData['allbank'].' testbank');
                            //die();
                            if (isset($paymentData['allbank'])) {
                                $paymentQuote->setData('bank_name', $paymentData['allbank']);
                                $paymentOrder->setData('bank_name', $paymentData['allbank']);
                            }
                            if (isset($paymentData['activebankowner'])) {
                                $paymentQuote->setData('bank_owner', $paymentData['activebankowner']);
                                $paymentOrder->setData('bank_owner', $paymentData['activebankowner']);
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                
            }
        }


        //return $this;
    }

}
