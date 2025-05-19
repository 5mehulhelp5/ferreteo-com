<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Helper\Validator\ProductPuqConfig;

use Aitoc\ProductUnitsAndQuantities\Api\Data\Source\QtyTypeInterface;
use Aitoc\ProductUnitsAndQuantities\Helper\ReplaceQtyOptionsHelper;
use Magento\Framework\Validator\AbstractValidator;

/**
 * Class ReplaceQtyValidator
 */
class QtyTypeValidator extends AbstractValidator
{
    const ERROR_MESSAGE = 'Invalid Qty Type value';

    /**
     * @var ReplaceQtyOptionsHelper
     */
    private $replaceQtyOptionsHelper;

    /**
     * ReplaceQtyValidator constructor.
     * @param ReplaceQtyOptionsHelper $replaceQtyOptionsHelper
     */
    public function __construct(ReplaceQtyOptionsHelper $replaceQtyOptionsHelper)
    {
        $this->replaceQtyOptionsHelper = $replaceQtyOptionsHelper;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        $this->_clearMessages();

        $possibleValues = $this->getPossibleQtyTypeValues();
        $isValid = in_array($value, $possibleValues);

        if (!$isValid) {
            $this->_addMessages([__(self::ERROR_MESSAGE)]);
        }

        return $isValid;
    }

    /**
     * @return array
     */
    private function getPossibleQtyTypeValues()
    {
        return [
            QtyTypeInterface::TYPE_STATIC,
            QtyTypeInterface::TYPE_DYNAMIC,
        ];
    }
}
