<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ibnab\BankInfo\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\Escaper;
use Magento\Payment\Helper\Data as PaymentHelper;

class BanktransferConfigProvider implements ConfigProviderInterface
{
    /**
     * @var string[]
     */
    protected $methodCode = Banktransfer::PAYMENT_METHOD_BANKTRANSFER_CODE;

    /**
     * @var Checkmo
     */
    protected $method;

    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * @param PaymentHelper $paymentHelper
     * @param Escaper $escaper
     */
    public function __construct(
        PaymentHelper $paymentHelper,
        Escaper $escaper
    ) {
        $this->escaper = $escaper;
        $this->method = $paymentHelper->getMethodInstance($this->methodCode);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return $this->method->isAvailable() ? [
            'payment' => [
                'banktransfer' => [
                    'allbank' => $this->getAllbank(),
                    'activebank' => $this->getActivebank(),
                    'activebankowner' => $this->getActivebankowner(),
                ],
            ],
        ] : [];
    }

    /**
     * Get allbank from config
     *
     * @return string
     */
    protected function getAllbank()
    {
        
        return $this->method->getAllbank();
    }

    /**
     * Get activebank to from config
     *
     * @return string
     */
    protected function getActivebank()
    {
        return $this->method->getActivebank();
    }
    /**
     * Get activebankowner to from config
     *
     * @return string
     */
    protected function getActivebankowner()
    {
        return $this->method->getActivebankowner();
    }
}
