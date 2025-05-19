<?php
namespace Magecomp\Cityandregionmanager\Block\Adminhtml\System\Zip;

use Magecomp\Cityandregionmanager\Model\ResourceModel\States\CollectionFactory as StatesFactory;

class Selectzipstates extends \Magento\Framework\DataObject implements \Magento\Framework\Option\ArrayInterface
{
    protected $_statesCollection;

    public function __construct(
        StatesFactory $statesCollection,
        array $data = []
    )
    {
        $this->_statesCollection    = $statesCollection;
        parent::__construct($data);
    }

    public function toOptionArray()
    {
        return $this->getArray();
    }

    public function getArray()
    {
        $collection = $this->_statesCollection->create()->getData();
        $arr = [['label' => 'Please Select...' ,'value' => '', 'attr' => 'selected']];
        foreach ($collection as $state)
        {
            $arr[] = ['label' => __($state['states_name']) ,'value' => $state['states_name']];
        }
        return $arr;
    }
}