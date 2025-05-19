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

use Aitoc\ProductUnitsAndQuantities\Api\Data\OrderItemPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\OrderItemPuqConfigRepositoryInterface;
use Aitoc\ProductUnitsAndQuantities\Model\ResourceModel\OrderItemPuqConfig as OrderItemResourceModel;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * Class OrderItemRepository
 */
class OrderItemPuqConfigRepository implements OrderItemPuqConfigRepositoryInterface
{
    /** @var array */
    private $byEntityIdCache;

    /** @var array */
    private $byOrderIdCache;

    /** @var OrderItemPuqConfigInterface */
    private $orderItemPuqConfigModel;

    /** @var OrderItemResourceModel */
    private $orderItemResourceModel;

    /** @var OrderItemPuqConfigFactory */
    private $orderItemModelFactory;

    /**
     * OrderItemPuqConfigPuqConfigRepository constructor.
     * @param OrderItemPuqConfigInterface $orderItemPuqConfigModel
     * @param OrderItemResourceModel $orderItemResourceModel
     * @param OrderItemPuqConfigFactory $orderItemModelFactory
     */
    public function __construct(
        OrderItemPuqConfigInterface $orderItemPuqConfigModel,
        OrderItemResourceModel $orderItemResourceModel,
        OrderItemPuqConfigFactory $orderItemModelFactory
    ) {
        $this->byEntityIdCache = [];
        $this->byOrderIdCache = [];
        $this->orderItemPuqConfigModel = $orderItemPuqConfigModel;
        $this->orderItemResourceModel = $orderItemResourceModel;
        $this->orderItemModelFactory = $orderItemModelFactory;
    }

    /**
     * @param OrderItemPuqConfigInterface $orderItemModel
     * @return OrderItemPuqConfigInterface
     * @throws CouldNotSaveException
     */
    public function save(OrderItemPuqConfigInterface $orderItemModel)
    {
        $model = $orderItemModel;

        try {
            $this->orderItemResourceModel->save($model);

            if ($model->getItemId()) {
                $this->removeFromCaches($model);
            }
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Unable to save settings %1', $model->getItemId()));
        }

        return $model;
    }

    /**
     * @param int $entityId
     * @return OrderItemPuqConfigInterface|mixed
     */
    public function getById($entityId)
    {
        return $this->getFromCacheOrLoad($entityId, OrderItemPuqConfigInterface::ITEM_ID, $this->byEntityIdCache);
    }

    /**
     * @param int $orderItemId
     * @return mixed
     */
    public function getByOrderItemId($orderItemId)
    {
        return $this->getFromCacheOrLoad(
            $orderItemId,
            OrderItemPuqConfigInterface::ORDER_ITEM_ID,
            $this->byOrderIdCache
        );
    }

    /**
     * @param int $entityId
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function deleteById($entityId)
    {
        return $this->delete($this->getById($entityId));
    }

    /**
     * @param int $entityId
     * @param string $field
     * @param array $cache
     * @return OrderItemPuqConfigInterface
     */
    private function getFromCacheOrLoad($entityId, $field, &$cache)
    {
        if (!$this->hasInCache($entityId, $cache)) {
            /** @var OrderItemPuqConfig $model */
            $model = $this->orderItemModelFactory->create();
            $this->orderItemResourceModel->load($model, $entityId, $field);

            if (!$model->getItemId()) {
                return null;
            }

            $this->addToCaches($model);
        }

        return $this->getFromCache($entityId, $cache);
    }

    /**
     * @param OrderItemPuqConfigInterface $model
     */
    private function addToCaches(OrderItemPuqConfigInterface $model)
    {
        $this->byEntityIdCache[$model->getItemId()] = $model;
        $this->byOrderIdCache[$model->getOrderItemId()] = $model;
    }

    /**
     * @param int $entityId
     * @param array $cache
     * @return OrderItemPuqConfigInterface
     */
    private function getFromCache($entityId, $cache)
    {
        return $cache[$entityId];
    }

    /**
     * @param OrderItemPuqConfigInterface $orderItemModel
     * @throws CouldNotDeleteException
     */
    public function delete(OrderItemPuqConfigInterface $orderItemModel)
    {
        try {
            $this->orderItemResourceModel->delete($orderItemModel);
            $this->removeFromCaches($orderItemModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
    }

    /**
     * @param int $entityId
     * @param array $cache
     * @return bool
     */
    private function hasInCache($entityId, $cache)
    {
        return array_key_exists($entityId, $cache);
    }

    /**
     * @param OrderItemPuqConfigInterface $orderItemPuqConfigModel
     */
    private function removeFromCaches(OrderItemPuqConfigInterface $orderItemPuqConfigModel)
    {
        $entityId = $orderItemPuqConfigModel->getItemId();

        unset($this->byEntityIdCache[$entityId]);
    }
}
