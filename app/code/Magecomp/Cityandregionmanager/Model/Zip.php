<?php
namespace Magecomp\Cityandregionmanager\Model;

use Magento\Framework\Model\AbstractModel;
use Magecomp\Cityandregionmanager\Api\Data\ZipInterface;
use Magento\Framework\DataObject\IdentityInterface;

class Zip extends AbstractModel implements ZipInterface, IdentityInterface
{
    const CACHE_TAG = 'magecomp_cityandregionmanager_zip';

    protected $_cacheTag = 'magecomp_cityandregionmanager_zip';

    protected $_eventPrefix = 'magecomp_cityandregionmanager_zip';

    protected function _construct()
    {
        $this->_init('Magecomp\Cityandregionmanager\Model\ResourceModel\Zip');
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

    public function getZipCode()
    {
        return $this->getData(self::ZIP_CODE);
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

    public function setZipCode($zip_code)
    {
        return $this->setData(self::ZIP_CODE, $zip_code);
    }
}