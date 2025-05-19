<?php
namespace Magecomp\Cityandregionmanager\Model\Source;

use Magento\Framework\App\ResourceConnection;

class Cityoptions implements \Magento\Framework\Option\ArrayInterface
{
    public function __construct(
        ResourceConnection $resource
    ) {
        $this->_resource = $resource;
    }

    public function toOptionArray()
    {
        $options = [];
        foreach ($this->getCitys() as $field) {
            $options[] = ['label' => $field['cities_name'], 'value' => $field['cities_name']];
        }
        return $options;
    }

    public function getCitys()
    {
        $adapter = $this->_resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $select = $adapter->select()
            ->from($this->_resource->getTableName('magecomp_cities'));

        return $adapter->fetchAll($select);
    }
}