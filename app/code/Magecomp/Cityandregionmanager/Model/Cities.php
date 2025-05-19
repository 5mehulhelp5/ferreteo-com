<?php
namespace Magecomp\Cityandregionmanager\Model;

use Magento\Framework\Model\AbstractModel;
use Magecomp\Cityandregionmanager\Api\Data\CitiesInterface;
use Magento\Framework\DataObject\IdentityInterface;

class Cities extends AbstractModel implements CitiesInterface, IdentityInterface
{
    const CACHE_TAG = 'magecomp_cityandregionmanager_cities';

    protected $_cacheTag = 'magecomp_cityandregionmanager_cities';

    protected $_eventPrefix = 'magecomp_cityandregionmanager_cities';

    protected function _construct()
    {
        $this->_init('Magecomp\Cityandregionmanager\Model\ResourceModel\Cities');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getId()
    {
        return $this->getData(self::ID);
    }

    public function getStatesName()
    {
        return $this->getData(self::STATES_NAME);
    }

    public function getCitiesName()
    {
        return $this->getData(self::CITIES_NAME);
    }

    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    public function setStatesName($states_name)
    {
        return $this->setData(self::STATES_NAME, $states_name);
    }

    public function setCitiesName($cities_name)
    {
        return $this->setData(self::CITIES_NAME, $cities_name);
    }
}