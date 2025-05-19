<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Block\System\Config\Form\Field;

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\MessagesInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\RoutePathInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\Data as PuqHelper;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\UrlInterface;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class Maxsaleqty
 */
class MaxSaleQty extends Field
{
    /** @var PuqHelper */
    private $puqHelper;

    /** @var UrlInterface */
    private $urlBuilder;

    /**
     * Maxsaleqty constructor.
     * @param Context $context
     * @param UrlInterface $urlBuilder
     * @param PuqHelper $puqHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        UrlInterface $urlBuilder,
        PuqHelper $puqHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->puqHelper = $puqHelper;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @inheritdoc
     */
    public function render(AbstractElement $element)
    {
        if ($this->puqHelper->getIsEnabledForCurrentStoreByConfig()) {
            $element->setDisabled('disabled');
            $element->setIsDisableInheritance(true);
            $url = $this->urlBuilder->getUrl(RoutePathInterface::DEFAULT_VALUE);
            $notifyMessage = __(MessagesInterface::CONFIG_MIN_MAX_QTY_NOTICE, $url);
            $element->setComment("<div class=\"_disabled\">{$notifyMessage}</div>");
        }

        return parent::render($element);
    }
}
