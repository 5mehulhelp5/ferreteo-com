<?php
namespace Magecomp\Cityandregionmanager\Model\Rule\Condition;

class Shippingcity extends \Magento\Rule\Model\Condition\AbstractCondition
{
    protected $sourceYesno;

    protected $orderFactory;

    protected  $cityOptions;

    protected $_checkoutSession;
    protected $customerFactory;
    protected $addressFactory;

    public function __construct(
        \Magento\Rule\Model\Condition\Context $context,
        \Magento\Config\Model\Config\Source\Yesno $sourceYesno,
         \Magecomp\Cityandregionmanager\Model\Source\Cityoptions $cityOptions,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Model\AddressFactory $addressFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->sourceYesno = $sourceYesno;
        $this->orderFactory = $orderFactory;
        $this->cityOptions = $cityOptions;
        $this->_checkoutSession = $checkoutSession;
        $this->customerFactory = $customerFactory;
        $this->addressFactory = $addressFactory;
    }

    public function loadAttributeOptions()
    {
        $this->setAttributeOption([
            'customer_first_order' => __('Shipping city')
        ]);
        return $this;
    }

    public function getInputType()
    {
       return 'select';
    }

    public function getValueElementType()
    {
        return 'select';
    }

    public function getValueSelectOptions()
    {
        if (!$this->hasData('value_select_options')) {
            $this->setData(
                'value_select_options',
                $this->cityOptions->toOptionArray()
            );
        }
        return $this->getData('value_select_options');
    }

    public function validate(\Magento\Framework\Model\AbstractModel $model)
    {
        $customerFactory = $this->customerFactory->create()->load($model->getCustomerId());
        $customerAddress = $this->addressFactory->create()->load($customerFactory->getDefaultBilling());
        $city = $customerAddress->getCity();
        $model->setData('customer_first_order', $city);
        return parent::validate($model);
    }
}