<?php
namespace Magecomp\Cityandregionmanager\Api\Data;

interface ZipInterface
{
    const ID            = 'entity_id';
    const STATES_NAME   = 'states_name';
    const CITIES_NAME   = 'cities_name';
    const ZIP_CODE      = 'zip_code';

    public function getId();

    public function getStatesName();

    public function getCitiesName();

    public function getZipCode();

    public function setId($id);

    public function setStatesName($states_name);

    public function setCitiesName($cities_name);

    public function setZipCode($zip_code);

}