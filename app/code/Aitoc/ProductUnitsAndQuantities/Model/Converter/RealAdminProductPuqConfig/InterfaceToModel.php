<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Model\Converter\RealAdminProductPuqConfig;

use Aitoc\ProductUnitsAndQuantities\Api\Data\RealProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Model\Converter\BaseInterfaceToModel;
use Aitoc\ProductUnitsAndQuantities\Model\RealProductPuqConfigFactory;

/**
 * Class InterfaceToModel
 */
class InterfaceToModel extends BaseInterfaceToModel
{
    /**
     * @var RealProductPuqConfigFactory
     */
    protected $modelFactory;

    /**
     * InterfaceToModel constructor.
     * @param RealProductPuqConfigFactory $modelFactory
     */
    public function __construct(RealProductPuqConfigFactory $modelFactory)
    {
        $this->modelFactory = $modelFactory;
    }

    /**
     * @param RealProductPuqConfigInterface $interface
     * @return array
     */
    protected function interfaceToModelData($interface)
    {
        return $interface->toArray();
    }

    /**
     * @inheritDoc
     */
    protected function getModelFactory()
    {
        return $this->modelFactory;
    }
}
