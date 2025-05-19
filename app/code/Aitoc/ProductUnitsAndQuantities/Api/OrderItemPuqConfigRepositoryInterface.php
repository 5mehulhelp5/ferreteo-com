<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Api;

use Aitoc\ProductUnitsAndQuantities\Api\Data\OrderItemPuqConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Interface OrderItemRepositoryInterface
 */
interface OrderItemPuqConfigRepositoryInterface
{
    /**
     * Save model.
     *
     * @param OrderItemPuqConfigInterface $orderItemPuqConfig
     * @return OrderItemPuqConfigInterface
     * @throws LocalizedException
     */
    public function save(OrderItemPuqConfigInterface $orderItemPuqConfig);

    /**
     * Retrieve model.
     *
     * @param int $entityId
     * @return OrderItemPuqConfigInterface
     * @throws LocalizedException
     */
    public function getById($entityId);

    /**
     * @param int $orderItemId
     * @return OrderItemPuqConfigInterface
     */
    public function getByOrderItemId($orderItemId);

    /**
     * Delete model.
     *
     * @param OrderItemPuqConfigInterface $orderItePuqConfig
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(OrderItemPuqConfigInterface $orderItePuqConfig);

    /**
     * Delete entity by ID.
     *
     * @param int $entityId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($entityId);
}
