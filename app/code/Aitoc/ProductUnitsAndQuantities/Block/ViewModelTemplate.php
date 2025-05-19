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

use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\View\Element\Template;

/**
 * Class ViewModelTemplate
 */
class ViewModelTemplate extends Template
{
    const VIEW_MODEL_CLASS_NAME_DATA_KEY = 'viewModelClassName';

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * ViewModelTemplate constructor.
     * @param Template\Context $context
     * @param ObjectManagerInterface $objectManager
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ObjectManagerInterface $objectManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->objectManager = $objectManager;
    }

    /**
     * @return mixed
     */
    public function getViewModel()
    {
        $viewModelClassName = $this->getData(self::VIEW_MODEL_CLASS_NAME_DATA_KEY);

        return $this->objectManager->create($viewModelClassName);
    }
}
