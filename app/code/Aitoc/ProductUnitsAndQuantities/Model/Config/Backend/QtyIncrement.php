<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Model\Config\Backend;

use Aitoc\ProductUnitsAndQuantities\Helper\Validator\ProductPuqConfig\StartEndIncQty\QtyIncrementValidator;
use Aitoc\ProductUnitsAndQuantities\Model\Data\SystemPuqConfigFactory;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

/**
 * Class StartQty
 */
class QtyIncrement extends Base
{
    /**
     * QtyIncrement constructor.
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param SystemPuqConfigFactory $systemPuqConfigFactory
     * @param QtyIncrementValidator $validator
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found -- QtyIncrementValidator
    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        SystemPuqConfigFactory $systemPuqConfigFactory,
        QtyIncrementValidator $validator,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $systemPuqConfigFactory,
            $validator,
            $resource,
            $resourceCollection,
            $data
        );
    }
}
