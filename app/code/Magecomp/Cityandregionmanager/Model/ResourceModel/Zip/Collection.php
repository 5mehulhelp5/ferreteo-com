<?php
namespace Magecomp\Cityandregionmanager\Model\ResourceModel\Zip;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magecomp\Cityandregionmanager\Api\Data\ZipInterface;

class Collection extends AbstractCollection
{
    protected $_idFieldName = ZipInterface::ID;

    protected $_eventPrefix = 'magecomp_cityandregionmanager_zip_collection';

    protected $_eventObject = 'cities_collection';

    protected function _construct()
    {
        $this->_init('Magecomp\Cityandregionmanager\Model\Zip', 'Magecomp\Cityandregionmanager\Model\ResourceModel\Zip');
    }

    protected function _toOptionArray($valueField = null, $labelField = 'title', $additional = [])
    {
        return parent::_toOptionArray($valueField, $labelField, $additional);
    }
}