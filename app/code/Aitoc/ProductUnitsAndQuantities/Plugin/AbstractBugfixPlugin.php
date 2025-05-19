<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Plugin;

use Magento\Framework\App\ProductMetadataInterface;

/**
 * Class AbstractBugfixPlugin
 */
abstract class AbstractBugfixPlugin
{
    /**
     * @var ProductMetadataInterface
     */
    protected $productMetadata;

    /**
     * @return bool
     */
    abstract protected function isBuggyCurrentCoreVersion();

    /**
     * AbstractBugfixPlugin constructor.
     * @param ProductMetadataInterface $productMetadata
     */
    public function __construct(ProductMetadataInterface $productMetadata)
    {
        $this->productMetadata = $productMetadata;
    }
}
