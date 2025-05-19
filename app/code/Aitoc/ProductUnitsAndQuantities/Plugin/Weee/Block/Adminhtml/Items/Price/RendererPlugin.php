<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Plugin\Weee\Block\Adminhtml\Items\Price;

use Aitoc\ProductUnitsAndQuantities\Api\OrderItemUnitsHelperInterface;
use Magento\Weee\Block\Adminhtml\Items\Price\Renderer;

/**
 * Class RendererPlugin
 */
class RendererPlugin
{
    /**
     * @var OrderItemUnitsHelperInterface
     */
    private $orderItemUnitsHelper;

    /**
     * DefaultRendererPlugin constructor.
     * @param OrderItemUnitsHelperInterface $orderItemUnitsHelper
     */
    public function __construct(OrderItemUnitsHelperInterface $orderItemUnitsHelper)
    {
        $this->orderItemUnitsHelper = $orderItemUnitsHelper;
    }

    /**
     * @param Renderer $subject
     * @param string $result
     * @return string
     */
    public function afterGetUnitPriceExclTaxHtml(Renderer $subject, $result)
    {
        return $this->addUnitsHtml($subject, $result);
    }

    /**
     * @param Renderer $subject
     * @param string $result
     * @return string
     */
    public function afterGetFinalUnitPriceExclTaxHtml(Renderer $subject, $result)
    {
        return $this->addUnitsHtml($subject, $result);
    }

    /**
     * @param Renderer $subject
     * @param string $result
     * @return string
     */
    public function getUnitPriceInclTaxHtml(Renderer $subject, $result)
    {
        return $this->addUnitsHtml($subject, $result);
    }

    /**
     * @param Renderer $subject
     * @param string $result
     * @return string
     */
    private function addUnitsHtml(Renderer $subject, $result)
    {
        $item = $subject->getItem();

        //todo: why so tricky?
        $orderItemId = $item->getOrderItemId();

        if (!$orderItemId) {
            $orderItemId = $item->getId();
        }

        $unitsHtml = $this->orderItemUnitsHelper->getUnitsHtml($orderItemId);

        if (!$unitsHtml) {
            return $result;
        }

        return $result . ' ' . $unitsHtml;
    }
}
