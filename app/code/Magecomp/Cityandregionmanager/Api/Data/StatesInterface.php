<?php
namespace Magecomp\Cityandregionmanager\Api\Data;

interface StatesInterface
{
    const ID                    = 'entity_id';
    const STATES_NAME           = 'states_name';
    const COUNTRY_ID           = 'country_id';

    public function getId();

    public function getStatesName();

    public function setId($id);

    public function setStatesName($states_name);

    public function setCountryId($country_id);

}