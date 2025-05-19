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

use Aitoc\ProductUnitsAndQuantities\Api\Model\Converter\ModelToInterfaceInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class InterfaceToModel
 */
abstract class BaseModelToInterface implements ModelToInterfaceInterface
{
    /**
     * @inheritDoc
     */
    public function modelToInterface(AbstractModel $model)
    {
        $interface = $this->getNewInterface();
        $this->setModelToInterface($model, $interface);

        return $interface;
    }

    /**
     * @return mixed - New Interface instance
     */
    protected function getNewInterface()
    {
        return $this->getInterfaceFactory()->create();
    }

    /**
     * @return mixed Interface items factory
     */
    abstract protected function getInterfaceFactory();

    /**
     * Move model data to interface object values.
     *
     * @param AbstractModel $model
     * @param object $interface
     */
    protected function setModelToInterface(AbstractModel $model, $interface)
    {
        $modelData = $model->getData();
        $this->setModelDataToInterface($modelData, $interface);
    }

    /**
     * @param array $modelData Data for AbstractModel
     * @param mixed $interface Interface instance.
     * @return mixed
     */
    abstract protected function setModelDataToInterface($modelData, $interface);
}
