<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Model;

use Aitoc\ProductUnitsAndQuantities\Api\Data\BaseAdminProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\RealProductPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\RealProductPuqConfigRepositoryInterface;
use Aitoc\ProductUnitsAndQuantities\Model\BaseModelRepositoryTraits\RepositoryDeleteTrait;
use Aitoc\ProductUnitsAndQuantities\Model\BaseModelRepositoryTraits\RepositoryLoadByFieldValueTrait;
use Aitoc\ProductUnitsAndQuantities\Model\BaseModelRepositoryTraits\RepositorySaveTrait;
use Aitoc\ProductUnitsAndQuantities\Model\Converter\RealAdminProductPuqConfig\InterfaceToModel as InterfaceToModelConverter;
use Aitoc\ProductUnitsAndQuantities\Model\Converter\RealAdminProductPuqConfig\ModelToInterface as ModelToInterfaceConverter;
use Aitoc\ProductUnitsAndQuantities\Model\ResourceModel\RealProductPuqConfig as AdminProductPuqConfigResourceModel;
use Aitoc\ProductUnitsAndQuantities\Model\ResourceModel\RealProductPuqConfig as RealAdminProductPuqConfigResourceModel;
use Aitoc\ProductUnitsAndQuantities\Model\ResourceModel\RealProductPuqConfig\Collection as RealProductPuqConfigCollection;
use Aitoc\ProductUnitsAndQuantities\Model\ResourceModel\RealProductPuqConfig\CollectionFactory as RealProductPuqConfigCollectionFactory;
use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * Class RealAdminProductPuqConfigRepository
 */
class RealProductPuqConfigRepository implements RealProductPuqConfigRepositoryInterface
{
    use RepositorySaveTrait {
        save as traitSave;
    }

    use RepositoryLoadByFieldValueTrait {
        getInterfaceOrNullByFieldValue as traitLoadByFieldValue;
        getInterfaceOrNullByFieldValues as traitLoadByFieldValues;
    }

    use RepositoryDeleteTrait {
        delete as traitDelete;
    }

    /** @var array */
    private $byEntityIdCache;

    /** @var array */
    private $byProductIdCache;

    /** @var AdminProductPuqConfigResourceModel */
    private $adminProductPuqConfigResourceModel;

    /** @var RealProductPuqConfigFactory */
    private $realAdminProductPuqConfigFactory;

    /** @var InterfaceToModelConverter */
    private $interfaceToModelConverter;

    /** @var ModelToInterfaceConverter */
    private $modelToInterfaceConverter;

    /**
     * @var RealProductPuqConfigCollectionFactory
     */
    private $realProductPuqConfigCollectionFactory;

    /**
     * RealAdminProductPuqConfigRepository constructor.
     * @param RealAdminProductPuqConfigResourceModel $adminProductPuqConfigResourceModel
     * @param RealProductPuqConfigFactory $realAdminProductPuqConfigFactory
     * @param RealProductPuqConfigCollectionFactory $realProductPuqConfigCollectionFactory
     * @param InterfaceToModelConverter $interfaceToModelConverter
     * @param ModelToInterfaceConverter $modelToInterfaceConverter
     */
    public function __construct(
        RealAdminProductPuqConfigResourceModel $adminProductPuqConfigResourceModel,
        RealProductPuqConfigFactory $realAdminProductPuqConfigFactory,
        RealProductPuqConfigCollectionFactory $realProductPuqConfigCollectionFactory,
        InterfaceToModelConverter $interfaceToModelConverter,
        ModelToInterfaceConverter $modelToInterfaceConverter
    ) {
        $this->byEntityIdCache = [];
        $this->byProductIdCache = [];
        $this->adminProductPuqConfigResourceModel = $adminProductPuqConfigResourceModel;
        $this->realAdminProductPuqConfigFactory = $realAdminProductPuqConfigFactory;
        $this->realProductPuqConfigCollectionFactory = $realProductPuqConfigCollectionFactory;
        $this->interfaceToModelConverter = $interfaceToModelConverter;
        $this->modelToInterfaceConverter = $modelToInterfaceConverter;
    }

    /**
     * @inheritdoc
     */
    public function save(RealProductPuqConfigInterface $adminProductPuqConfig)
    {
        return $this->traitSave($adminProductPuqConfig);
    }

    /**
     * @param int $productId
     * @param int $storeId
     * @return RealProductPuqConfigInterface
     */
    public function getByProductIdAndStoreId($productId, $storeId)
    {
        return $this->getInterfaceOrNullByFieldValues([
            RealProductPuqConfigInterface::PRODUCT_ID => $productId,
            RealProductPuqConfigInterface::STORE_ID => $storeId,
        ]);
    }

    /**
     * @param array $fieldValues
     * @return RealProductPuqConfigInterface|null|object
     */
    private function getInterfaceOrNullByFieldValues($fieldValues)
    {
        return $this->traitLoadByFieldValues($fieldValues);
    }

    /**
     * @param int $entityId
     * @return void
     * @throws CouldNotDeleteException
     */
    public function deleteById($entityId)
    {
        $this->delete($this->getById($entityId));
    }

    /**
     * @param RealProductPuqConfigInterface $interface
     * @throws CouldNotDeleteException
     */
    public function delete(RealProductPuqConfigInterface $interface)
    {
        try {
            //todo implement by interface friendly delete trait
            $this->adminProductPuqConfigResourceModel->delete($interface);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
    }

    /**
     * @param int $entityId
     * @return RealProductPuqConfigInterface|mixed
     */
    public function getById($entityId)
    {
        if (!$model = $this->getInterfaceOrNullByFieldValue($entityId, RealProductPuqConfigInterface::ID)) {
            return null;
        }

        return $model;
    }

    /**
     * @param string $fieldName
     * @param mixed $fieldValue
     * @return RealProductPuqConfigInterface|null|object
     */
    private function getInterfaceOrNullByFieldValue($fieldName, $fieldValue)
    {
        return $this->traitLoadByFieldValue($fieldName, $fieldValue);
    }

    /**
     * @inheritDoc
     */
    protected function getInterfaceToModelConverter()
    {
        return $this->interfaceToModelConverter;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceModel()
    {
        return $this->adminProductPuqConfigResourceModel;
    }

    /**
     * @inheritdoc
     */
    protected function getModelClass()
    {
        return RealProductPuqConfig::class;
    }

    /**
     * @inheritDoc
     */
    protected function interfaceToModelData(BaseAdminProductPuqConfigInterface $interface)
    {
        return $interface->toArray();
    }

    /**
     * @inheritDoc
     */
    protected function getModelFactory()
    {
        return $this->realAdminProductPuqConfigFactory;
    }

    /**
     * @inheritDoc
     */
    protected function interfaceToModel($interface)
    {
        return $this->interfaceToModelConverter->interfaceToModel($interface);
    }

    /**
     * @inheritDoc
     */
    protected function modelToInterface($model)
    {
        return $this->modelToInterfaceConverter->modelToInterface($model);
    }

    /**
     * @return RealProductPuqConfigCollection
     */
    protected function getCollection()
    {
        return $this->realProductPuqConfigCollectionFactory->create();
    }
}
