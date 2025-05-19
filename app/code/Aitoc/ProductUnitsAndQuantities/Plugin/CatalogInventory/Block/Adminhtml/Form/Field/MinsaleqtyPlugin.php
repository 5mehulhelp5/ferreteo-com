<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Plugin\CatalogInventory\Block\Adminhtml\Form\Field;

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\MessagesInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\RoutePathInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\Data as PuqHelper;
use Magento\Backend\Model\UrlInterface;
use Magento\CatalogInventory\Block\Adminhtml\Form\Field\Minsaleqty as FieldMinsaleqty;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class Minsaleqty
 */
class MinsaleqtyPlugin
{
    const PUQ_SECTION_NAME = 'product_units_and_quantities';

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var PuqHelper
     */
    private $puqHelper;

    /**
     * Minsaleqty constructor.
     *
     * @param UrlInterface $urlBuilder
     * @param PuqHelper $puqHelper
     */
    public function __construct(UrlInterface $urlBuilder, PuqHelper $puqHelper)
    {
        $this->urlBuilder = $urlBuilder;
        $this->puqHelper = $puqHelper;
    }

    /**
     * @param FieldMinsaleqty $subject
     * @param AbstractElement $element
     */
    public function beforeRender(FieldMinsaleqty $subject, AbstractElement $element)
    {
        if (!$this->shouldBeDisabled($element)) {
            return;
        }

        $url = $this->urlBuilder->getUrl(RoutePathInterface::DEFAULT_VALUE);
        $notifyMessage = __(MessagesInterface::CONFIG_MIN_MAX_QTY_NOTICE, $url);

        $element->setComment("<div class=\"_disabled\">{$notifyMessage}</div>");
        $element->setDisabled(true);
        $element->setIsDisableInheritance(true);
    }

    /**
     * @param AbstractElement $element
     * @return bool
     */
    public function shouldBeDisabled(AbstractElement $element)
    {
        if ($this->isPuqConfigPage($element)) {
            return false;
        }

        return $this->puqHelper->getIsEnabledForCurrentStoreByConfig();
    }

    /**
     * @param AbstractElement $element
     * @return bool
     */
    public function isPuqConfigPage(AbstractElement $element)
    {
        $elementPath = $element->getData('field_config/path');

        return $this->startsWith($elementPath, self::PUQ_SECTION_NAME);
    }

    /**
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    private function startsWith($haystack, $needle)
    {
        $length = strlen($needle);

        return (substr($haystack, 0, $length) === $needle);
    }
}
