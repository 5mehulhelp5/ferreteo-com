<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Helper;

use Aitoc\ProductUnitsAndQuantities\Api\Data\OrderItemPuqConfigInterface;
use Aitoc\ProductUnitsAndQuantities\Api\OrderItemPuqConfigRepositoryInterface;
use Aitoc\ProductUnitsAndQuantities\Api\OrderItemUnitsHelperInterface;

/**
 * Class OrderItemUnitsHelper
 */
class OrderItemUnitsHelper implements OrderItemUnitsHelperInterface
{
    /**
     * @var OrderItemPuqConfigRepositoryInterface
     */
    private $orderItemPuqConfigRepository;

    /**
     * OrderItemUnitsHelper constructor.
     * @param OrderItemPuqConfigRepositoryInterface $orderItemPuqConfigRepository
     */
    public function __construct(OrderItemPuqConfigRepositoryInterface $orderItemPuqConfigRepository)
    {
        $this->orderItemPuqConfigRepository = $orderItemPuqConfigRepository;
    }

    /**
     * @inheritdoc
     */
    public function getUnitsHtml($orderItemId)
    {
        $orderItemPuqConfig = $this->getOrderItemPuqConfig($orderItemId);

        return $this->orderItemPuqConfigToUnitsString($orderItemPuqConfig);
    }

    /**
     * @param int $orderItemId
     * @return OrderItemPuqConfigInterface
     */
    private function getOrderItemPuqConfig($orderItemId)
    {
        return $this->orderItemPuqConfigRepository->getByOrderItemId($orderItemId);
    }

    /**
     * @param OrderItemPuqConfigInterface|null $orderItemPuqConfig
     * @return string
     */
    private function orderItemPuqConfigToUnitsString(OrderItemPuqConfigInterface $orderItemPuqConfig = null)
    {
        if (!$orderItemPuqConfig) {
            return '';
        }

        return $orderItemPuqConfig->getPricePerDivider() . ' ' . $orderItemPuqConfig->getPricePer();
    }
}
