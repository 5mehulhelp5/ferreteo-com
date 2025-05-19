<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Block;

use Aitoc\ProductUnitsAndQuantities\Api\ViewModel\PuqConfigInterface as PuqConfigViewModelInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\View\Element\Template;

/**
 * Class PuqConfig
 */
class PuqConfig extends ViewModelTemplate
{
    protected $_template = 'Aitoc_ProductUnitsAndQuantities::puq_config.phtml';

    /**
     * PuqConfig constructor.
     * @param Template\Context $context
     * @param ObjectManagerInterface $objectManager
     * @param array $data
     */
    public function __construct(Template\Context $context, ObjectManagerInterface $objectManager, array $data = [])
    {
        $data[self::VIEW_MODEL_CLASS_NAME_DATA_KEY] = PuqConfigViewModelInterface::class;
        parent::__construct($context, $objectManager, $data);
    }
}
