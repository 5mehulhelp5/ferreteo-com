<?php
namespace Magecomp\Cityandregionmanager\Model\ResourceModel\Cities;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magecomp\Cityandregionmanager\Api\Data\CitiesInterface;

class Collection extends AbstractCollection
{
    protected $_idFieldName = CitiesInterface::ID;

    protected $_eventPrefix = 'magecomp_cityandregionmanager_cities_collection';

    protected $_eventObject = 'cities_collection';

    protected function _construct()
    {
        $this->_init('Magecomp\Cityandregionmanager\Model\Cities', 'Magecomp\Cityandregionmanager\Model\ResourceModel\Cities');
    }

    protected function _toOptionArray($valueField = null, $labelField = 'title', $additional = [])
    {
        return parent::_toOptionArray($valueField, $labelField, $additional);
    }
}