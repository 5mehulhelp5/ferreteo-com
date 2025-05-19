<?php
/**
 * Acx_ZoomEnvios Magento Extension
 **/

namespace Acx\ZoomEnvios\Plugin\Sales\Model\AdminOrder;


/**
 * Order create model plugin
 */
class Create
{
    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     */
    public function __construct(
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
    ) {
        $this->quoteRepository = $quoteRepository;
    }


    /**
     * Quote saving before plugin
     * @return $this
     */
    public function beforeSaveQuote(
        \Magento\Sales\Model\AdminOrder\Create $subject
    )
    {
        $cartId = $subject->getQuote()->getId();
        $addressInformation = $subject->getQuote()->getShippingAddress();
        $extAttributes = $addressInformation->getExtensionAttributes();
        $pickupOffice = $extAttributes->getPickupOffice();
        //$quote = $this->quoteRepository->getActive($cartId);
        if (!is_null($pickupOffice)) {
            $subject->getQuote()->setPickupOffice($pickupOffice);
        }
    }

}
