<?php
namespace Magecomp\Cityandregionmanager\Observer;

class Shippingcityconditionobserver implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $additional = $observer->getAdditional();
        $conditions = (array) $additional->getConditions();

        $conditions = array_merge_recursive($conditions, [
            $this->getCustomerFirstOrderCondition()
        ]);

        $additional->setConditions($conditions);
        return $this;
    }

    private function getCustomerFirstOrderCondition()
    {
        return [
            'label'=> __('Shipping city'),
            'value'=> \Magecomp\Cityandregionmanager\Model\Rule\Condition\Shippingcity::class
        ];
    }
}