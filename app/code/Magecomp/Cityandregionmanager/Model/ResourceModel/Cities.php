<?php
namespace Magecomp\Cityandregionmanager\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magecomp\Cityandregionmanager\Api\Data\CitiesInterface;

class Cities extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('magecomp_cities', CitiesInterface::ID);
    }
}