<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Model\BaseModelRepositoryTraits;

use Aitoc\ProductUnitsAndQuantities\Api\Model\Converter\InterfaceToModelInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Trait RepositorySaveTrait
 */
trait RepositorySaveTrait
{
    /**
     * @param object $interface
     * @return object
     * @throws CouldNotSaveException
     */
    public function save($interface)
    {
        $model = $this->interfaceToModel($interface);

        try {
            $this->getResourceModel()->save($model);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Unable to save %1 %2', get_class($model), $model->getId()));
        }

        return $interface;
    }

    /**
     * @param object $interface
     * @return AbstractModel
     */
    protected function interfaceToModel($interface)
    {
        return $this->getInterfaceToModelConverter()->interfaceToModel($interface);
    }

    /**
     * Convert interface instance to Model
     *
     * @return InterfaceToModelInterface
     */
    abstract protected function getInterfaceToModelConverter();

    /**
     * @return AbstractDb
     */
    abstract protected function getResourceModel();
}
