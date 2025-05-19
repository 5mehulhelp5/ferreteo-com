<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Plugin\Sales\Block\Adminhtml\Order\View\Items\Renderer;

use Aitoc\ProductUnitsAndQuantities\Api\OrderItemUnitsHelperInterface;
use Magento\Sales\Block\Adminhtml\Order\View\Items\Renderer\DefaultRenderer;

/**
 * Class DefaultRendererPlugin
 */
class DefaultRendererPlugin
{
    /**
     * @var OrderItemUnitsHelperInterface
     */
    private $orderItemUnitsHelper;

    /**
     * @var string
     */
    private $code;

    /**
     * DefaultRendererPlugin constructor.
     * @param OrderItemUnitsHelperInterface $orderItemUnitsHelper
     */
    public function __construct(OrderItemUnitsHelperInterface $orderItemUnitsHelper)
    {
        $this->orderItemUnitsHelper = $orderItemUnitsHelper;
    }

    /**
     * @param DefaultRenderer $subject
     * @param string $code
     * @return void
     */
    public function beforeDisplayPriceAttribute(DefaultRenderer $subject, $code)
    {
        $this->code = $code;
    }

    /**
     * @param DefaultRenderer $subject
     * @param string $result
     * @return string
     */
    public function afterDisplayPriceAttribute(DefaultRenderer $subject, $result)
    {
        if (!in_array($this->code, ['original_price', 'price'])) {
            return $result;
        }

        $unitsString = $this->getUnitsHtml($subject);

        if (!$unitsString) {
            return $result;
        }

        return $result . ' ' . $unitsString;
    }

    /**
     * @param DefaultRenderer $subject
     * @return string
     */
    private function getUnitsHtml(DefaultRenderer $subject)
    {
        $orderItemId = $subject->getPriceDataObject()->getId();

        return $this->orderItemUnitsHelper->getUnitsHtml($orderItemId);
    }
}
