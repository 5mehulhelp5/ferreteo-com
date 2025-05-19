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
 * Class AddStoreIdField
 */
class AddStoreIdField implements OperationInterface
{
    /**
     * @inheritdoc
     */
    public function execute(SchemaSetupInterface $setup)
    {
        $baseTable = $setup->getTable(SetupConstantsInterface::PRODUCT_PUQ_CONFIG_TABLE_NAME);
        $connection = $setup->getConnection();
        //todo: make it as reference column
        $connection->addColumn(
            $baseTable,
            SetupConstantsInterface::FIELD_STORE_ID,
            ['type'=> Table::TYPE_SMALLINT, 'nullable' => false, 'default' => 0, 'comment' => 'Store Id']
        );
    }
}
