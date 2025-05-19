<?php
namespace Magecomp\Cityandregionmanager\Model;

use Magento\Framework\Model\AbstractModel;
use Magecomp\Cityandregionmanager\Api\Data\StatesInterface;
use Magento\Framework\DataObject\IdentityInterface;

class States extends AbstractModel implements StatesInterface, IdentityInterface
{
    const CACHE_TAG = 'magecomp_cityandregionmanager_states';

    protected $_cacheTag = 'magecomp_cityandregionmanager_states';

    protected $_eventPrefix = 'magecomp_cityandregionmanager_states';

    protected function _construct()
    {
        $this->_init('Magecomp\Cityandregionmanager\Model\ResourceModel\States');
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

    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    public function setStatesName($states_name)
    {
        return $this->setData(self::STATES_NAME, $states_name);
    }
    public function setCountryId($country_id)
    {
        return $this->setData(self::COUNTRY_ID, $country_id);
    }
}