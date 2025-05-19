<?php
namespace Magecomp\Cityandregionmanager\Model\ResourceModel\States;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magecomp\Cityandregionmanager\Api\Data\StatesInterface;

class Collection extends AbstractCollection
{
    protected $_idFieldName = StatesInterface::ID;

    protected $_eventPrefix = 'magecomp_cityandregionmanager_states_collection';

    protected $_eventObject = 'states_collection';

    protected function _construct()
    {
        $this->_init('Magecomp\Cityandregionmanager\Model\States', 'Magecomp\Cityandregionmanager\Model\ResourceModel\States');
    }

    protected function _toOptionArray($valueField = null, $labelField = 'title', $additional = [])
    {
        return parent::_toOptionArray($valueField, $labelField, $additional);
    }
}