<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Plugin\Config\Block\System\Config\Form;

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\CatalogInventoryQtyFieldsInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\MessagesInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\RoutePathInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\Data as PuqHelper;
use Magento\Backend\Model\UrlInterface;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class FieldPlugin
 */
class FieldPlugin
{
    const PUQ_SECTION_NAME = 'product_units_and_quantities';

    const QTY_PARAMS_KEYS = [
        CatalogInventoryQtyFieldsInterface::MIN_SALE_QTY,
        CatalogInventoryQtyFieldsInterface::MAX_SALE_QTY,
        CatalogInventoryQtyFieldsInterface::ENABLE_QTY_INCREMENTS,
        CatalogInventoryQtyFieldsInterface::QTY_INCREMENTS,
    ];

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
     * @param Field $subject
     * @param AbstractElement $element
     */
    public function beforeRender(Field $subject, AbstractElement $element)
    {
        if (!$this->shouldBeDisabled($element)) {
            return;
        }

        $url = $this->urlBuilder->getUrl(RoutePathInterface::DEFAULT_VALUE);
        $notifyMessage = __(MessagesInterface::CONFIG_MIN_MAX_QTY_NOTICE, $url);

        $element->setComment("<div class=\"_disabled\">{$notifyMessage}</div>");
        $element->setDisabled(true);
        $element->setIsDisableInheritance(true);
        $element->setReadonly(true);
    }

    /**
     * @param AbstractElement $element
     * @return bool
     */
    private function shouldBeDisabled(AbstractElement $element)
    {
        if (!$this->puqHelper->getIsEnabledForCurrentStoreByConfig()) {
            return false;
        }

        if (!$this->isQtyParamField($element)) {
            return false;
        }

        if ($this->isPuqConfigPage($element)) {
            return false;
        }

        return true;
    }

    /**
     * @param AbstractElement $element
     * @return bool
     */
    private function isQtyParamField(AbstractElement $element)
    {
        $elementId = $element->getData('field_config/id');

        return in_array($elementId, self::QTY_PARAMS_KEYS);
    }

    /**
     * @param AbstractElement $element
     * @return bool
     */
    private function isPuqConfigPage(AbstractElement $element)
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
