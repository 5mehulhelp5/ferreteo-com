<?php
namespace Magecomp\Cityandregionmanager\Block\Adminhtml\System\Countries;

use Magento\Directory\Model\ResourceModel\Country\CollectionFactory as CountryCollectionFactory;
use Magento\Directory\Model\CountryFactory;

class Selectcountries extends \Magento\Framework\DataObject implements \Magento\Framework\Option\ArrayInterface
{
    protected $_countryCollection;
    protected $_countryFactory;
    public function __construct(
        CountryCollectionFactory $countryCollection,
        CountryFactory $countryFactory,
        array $data = []
    )
    {
        $this->_countryCollection    = $countryCollection;
        $this->_countryFactory = $countryFactory;
        parent::__construct($data);
    }

    public function toOptionArray()
    {
        return $this->getArray();
    }

    public function getArray()
    {
        $collection = $this->_countryCollection->create()->loadByStore();
        $arr = [];
        $countries = $this->_countryFactory->create();
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);

        $arr[] = ['label' => __("Please Select..") ,'value' => ''];
        foreach ($collection as $country)
        {
            $countryname = $countries->loadByCode($country['country_id'])->getName();

            $arr[] = ['label' => __($countryname) ,'value' => $country['country_id']];
        }
        return $arr;
    }
}