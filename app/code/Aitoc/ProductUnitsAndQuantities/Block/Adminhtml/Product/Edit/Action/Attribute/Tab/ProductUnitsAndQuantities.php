<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Block\Adminhtml\Product\Edit\Action\Attribute\Tab;

use Aitoc\ProductUnitsAndQuantities\Api\PuqConfigurationInterface;
use Aitoc\ProductUnitsAndQuantities\Model\Data\ResultAdminProductPuqConfig;
use Aitoc\ProductUnitsAndQuantities\Model\Data\ResultAdminProductPuqConfigFactory;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class ProductUnitsAndQuantities extends Widget implements TabInterface
{
    /** @var ResultAdminProductPuqConfigFactory */
    private $puqConfiguration;

    public function __construct(
        Context $context,
        PuqConfigurationInterface $puqConfigurationInterface,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->puqConfiguration = $puqConfigurationInterface;
    }

    public function getTabLabel()
    {
        return __('Product Units and Quantities');
    }

    public function getTabTitle()
    {
        return __('Product Units and Quantities');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getFieldSuffix()
    {
        return 'product_units_and_quantities';
    }

    /**
     * @return ResultAdminProductPuqConfig
     */
    public function getDefaultConfig()
    {
        return $this->puqConfiguration->getRealPuqSystemConfig();
    }
}
