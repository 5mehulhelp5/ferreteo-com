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

use Aitoc\ProductUnitsAndQuantities\Helper\ReplaceQtyOptionsHelper;
use Aitoc\ProductUnitsAndQuantities\Helper\Validator\ProductPuqConfig\Base\BaseInArrayValidator;

/**
 * Class ReplaceQtyValidator
 */
class ReplaceQtyValidator extends BaseInArrayValidator
{
    /**
     * @var ReplaceQtyOptionsHelper
     */
    private $replaceQtyOptionsHelper;

    /**
     * ReplaceQtyValidator constructor.
     * @param ReplaceQtyOptionsHelper $replaceQtyOptionsHelper
     * @throws \Zend_Validate_Exception
     */
    public function __construct(ReplaceQtyOptionsHelper $replaceQtyOptionsHelper)
    {
        $this->replaceQtyOptionsHelper = $replaceQtyOptionsHelper;

        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function getErrorMessage()
    {
        return 'Invalid Replace Qty value';
    }

    /**
     * @inheritDoc
     */
    protected function getPossibleValues()
    {
        return array_keys($this->replaceQtyOptionsHelper->getValues());
    }
}
