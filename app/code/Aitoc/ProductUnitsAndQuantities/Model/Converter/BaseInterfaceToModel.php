<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Model\Converter;

use Aitoc\ProductUnitsAndQuantities\Api\Model\Converter\InterfaceToModelInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class InterfaceToModel
 */
abstract class BaseInterfaceToModel implements InterfaceToModelInterface
{
    /**
     * @inheritDoc
     */
    public function interfaceToModel($interface)
    {
        $modelData = $this->interfaceToModelData($interface);
        $model = $this->createNewModelWithData($modelData);

        return $model;
    }

    /**
     * @param object $interface
     * @return array
     */
    abstract protected function interfaceToModelData($interface);

    /**
     * @param array $modelData
     * @return AbstractModel
     */
    protected function createNewModelWithData($modelData)
    {
        $model = $this->createNewModel();
        $model->setData($modelData);

        return $model;
    }

    /**
     * @return AbstractModel
     */
    protected function createNewModel()
    {
        return $this->getModelFactory()->create();
    }

    /**
     * @return mixed
     */
    abstract protected function getModelFactory();
}
