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

use Magento\Framework\DataObject;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Trait RepositorySaveTrait
 */
trait RepositoryLoadByFieldValueTrait
{
    /**
     * @return AbstractDb
     */
    abstract protected function getResourceModel();

    /**
     * @return AbstractCollection
     */
    abstract protected function getCollection();

    /**
     * Return Model Factory
     */
    abstract protected function getModelFactory();

    /**
     * @param AbstractModel $model
     */
    abstract protected function modelToInterface($model);

    /**
     * @param string $fieldName
     * @param string $fieldValue
     * @return object|null
     */
    protected function getInterfaceOrNullByFieldValue($fieldName, $fieldValue)
    {
        if (!$model = $this->getModelOrNullByFieldValue($fieldName, $fieldValue)) {
            return null;
        }

        return $this->modelToInterface($model);
    }

    /**
     * @param array $fieldValues
     * @return object|null
     */
    protected function getInterfaceOrNullByFieldValues($fieldValues)
    {
        if (!$model = $this->getModelOrNullByFieldValues($fieldValues)) {
            return null;
        }

        return $this->modelToInterface($model);
    }

    /**
     * @param string $fieldName
     * @param mixed $fieldValue
     * @return AbstractModel|null
     */
    protected function getModelOrNullByFieldValue($fieldName, $fieldValue)
    {
        $model = $this->createNewModel();
        $resourceModel = $this->getResourceModel();
        $resourceModel->load($model, $fieldValue, $fieldName);

        if (!$model->hasData()) {
            return null;
        }

        return $model;
    }

    /**
     * @param $fieldValues
     * @return AbstractModel|DataObject|null
     */
    protected function getModelOrNullByFieldValues($fieldValues)
    {
        $collection = $this->getCollection();

        foreach ($fieldValues as $fieldName => $fieldValue) {
            $collection->addFieldToFilter($fieldName, $fieldValue);
        }

        $model = $collection->getFirstItem();

        return $model->isEmpty() ? null : $model;
    }

    /**
     * @return AbstractModel
     */
    protected function createNewModel()
    {
        return $this->getModelFactory()->create();
    }
}
