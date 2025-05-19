<?php
namespace Magecomp\Cityandregionmanager\Api\Data;

interface CitiesInterface
{
    const ID            = 'entity_id';
    const STATES_NAME   = 'states_name';
    const CITIES_NAME   = 'cities_name';

    public function getId();

    public function getStatesName();

    public function getCitiesName();

    public function setId($id);

    public function setStatesName($states_name);

    public function setCitiesName($cities_name);

}