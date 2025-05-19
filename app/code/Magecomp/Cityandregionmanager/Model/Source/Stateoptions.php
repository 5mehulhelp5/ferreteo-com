<?php
namespace Magecomp\Cityandregionmanager\Model\Source;

use Magento\Framework\App\ResourceConnection;

class Stateoptions implements \Magento\Framework\Option\ArrayInterface
{
    public function __construct(
        ResourceConnection $resource
    ) {
        $this->_resource = $resource;
    }

    public function toOptionArray()
    {
        $options = [];
        foreach ($this->getStates() as $field) {
            $options[] = ['label' => $field['states_name'], 'value' => $field['entity_id']];
        }
        return $options;
    }

    public function toArray()
    {
        return [0 => __('No'), 1 => __('Yes')];
    }
    public function getStates()
    {
        $adapter = $this->_resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $select = $adapter->select()
            ->from($this->_resource->getTableName('magecomp_states'));
        return $adapter->fetchAll($select);
    }
}