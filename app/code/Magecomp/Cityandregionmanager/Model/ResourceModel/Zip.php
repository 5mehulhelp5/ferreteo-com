<?php
namespace Magecomp\Cityandregionmanager\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magecomp\Cityandregionmanager\Api\Data\ZipInterface;

class Zip extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('magecomp_zip', ZipInterface::ID);
    }
}