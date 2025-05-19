<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Setup\Operations;

use Aitoc\ProductUnitsAndQuantities\Api\Setup\Operations\OperationInterface;
use Aitoc\ProductUnitsAndQuantities\Api\Setup\SetupConstantsInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class AddUseConfigParamsField
 */
class AddUseConfigParamsField implements OperationInterface
{
    /**
     * @inheritDoc
     */
    public function execute(SchemaSetupInterface $setup)
    {
        $baseTable = $setup->getTable(SetupConstantsInterface::PRODUCT_PUQ_CONFIG_TABLE_NAME);
        $connection = $setup->getConnection();
        $connection->addColumn($baseTable, SetupConstantsInterface::USE_CONFIG_PARAMS_FIELD_NAME, Table::TYPE_TEXT);
    }
}
