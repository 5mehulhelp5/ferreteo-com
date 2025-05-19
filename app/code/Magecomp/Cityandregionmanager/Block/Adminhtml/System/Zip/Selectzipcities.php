<?php
namespace Magecomp\Cityandregionmanager\Block\Adminhtml\System\Zip;

use Magecomp\Cityandregionmanager\Model\ResourceModel\Cities\CollectionFactory as CitiesFactory;

class Selectzipcities extends \Magento\Framework\DataObject implements \Magento\Framework\Option\ArrayInterface
{
    protected $_citiesCollection;

    public function __construct(
        CitiesFactory $citiesCollection,
        array $data = []
    )
    {
        $this->_citiesCollection    = $citiesCollection;
        parent::__construct($data);
    }

    public function toOptionArray()
    {
        return $this->getArray();
    }

    public function getArray()
    {
        $collection = $this->_citiesCollection->create()->getData();
        $arr = [['label' => 'Please Select...' ,'value' => '', 'attr' => 'selected']];
        foreach ($collection as $cities)
        {
            $arr[] = ['label' => __($cities['cities_name']) ,'value' => $cities['cities_name']];
        }
        return $arr;
    }
}