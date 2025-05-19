<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Model\Data;

use Aitoc\ProductUnitsAndQuantities\Api\ResultFrontendProductPuqConfigFactoryInterface;
use Magento\Framework\ObjectManagerInterface;

/**
 * Factory class for @see \Aitoc\ProductUnitsAndQuantities\Model\Data\ResultFrontendProductPuqConfig
 */
class ResultFrontendProductPuqConfigFactory implements ResultFrontendProductPuqConfigFactoryInterface
{
    /**
     * Object Manager instance
     *
     * @var ObjectManagerInterface
     */
    protected $_objectManager = null;

    /**
     * Instance name to create
     *
     * @var string
     */
    protected $_instanceName = null;

    /**
     * Factory constructor
     *
     * @param ObjectManagerInterface $objectManager
     * @param string $instanceName
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        $instanceName = ResultFrontendProductPuqConfig::class
    ) {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return ResultFrontendProductPuqConfig
     */
    public function create(array $data = [])
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }
}
