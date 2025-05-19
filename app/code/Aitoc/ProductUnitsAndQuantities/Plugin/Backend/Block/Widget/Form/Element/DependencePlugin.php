<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Plugin\Backend\Block\Widget\Form\Element;

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\PuqConfigFieldIdsInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\ReplaceQtyInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\Data as PuqHelper;
use Magento\Backend\Block\Widget\Form\Element\Dependence;
use Magento\Config\Model\Config\Structure\Element\Dependency\Field;

/**
 * Class DependencePlugin
 */
class DependencePlugin
{
    const QTY_INCREMENTS_ID = 'cataloginventory_item_options_enable_qty_increments';

    /**
     * @var PuqHelper
     */
    private $puqHelper;

    /**
     * DependencePlugin constructor.
     * @param PuqHelper $puqHelper
     */
    public function __construct(PuqHelper $puqHelper)
    {
        $this->puqHelper = $puqHelper;
    }

    /**
     * @param Dependence $dependence
     * @param string $fieldName
     * @param string $fieldNameFrom
     * @param Field|string $refField
     * @return void
     */
    public function beforeAddFieldDependence(Dependence $dependence, $fieldName, $fieldNameFrom, $refField)
    {
        if (!$this->isQtyIncrements($refField)) {
            return null;
        }

        if (!$this->shouldBeDisabled()) {
            return null;
        }

        $this->addDisabledConfig($dependence);
    }

    /**
     * @param Field|string $refField
     * @return bool
     */
    private function isQtyIncrements($refField)
    {
        if (is_object($refField)) {
            $refField = $refField->getId();
        }

        return self::QTY_INCREMENTS_ID === $refField;
    }

    /**
     * @return bool
     */
    private function shouldBeDisabled()
    {
        $replaceQty = $this->puqHelper->getConfigFieldValue(PuqConfigFieldIdsInterface::REPLACE_QTY);

        return $replaceQty != ReplaceQtyInterface::OFF;
    }

    /**
     * @param Dependence $dependence
     */
    private function addDisabledConfig(Dependence $dependence)
    {
        $configOptions = [
            'levels_up' => 1,
            // Default config not extended in frontend.
            // See https://github.com/magento/magento2/pull/16001

            'can_edit_price' => false,
            // Dirty hack to prevent enable field.
            // See /vendor/magento/magento2-base/lib/web/mage/adminhtml/form.js:472
            // Todo: use as bugfix when will be fixed or new flags added to source
        ];

        $dependence->addConfigOptions($configOptions);
    }
}
