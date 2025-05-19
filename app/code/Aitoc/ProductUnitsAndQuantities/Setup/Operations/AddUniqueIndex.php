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
use Magento\Framework\DB\Adapter\AdapterInterface;

class AddUniqueIndex implements OperationInterface
{
    public function execute(SchemaSetupInterface $setup)
    {
        $baseTable = $setup->getTable(SetupConstantsInterface::PRODUCT_PUQ_CONFIG_TABLE_NAME);
        $connection = $setup->getConnection();
        //todo: make it as reference column
        $connection->addIndex(
            $baseTable,
            $connection->getIndexName(
                SetupConstantsInterface::PRODUCT_PUQ_CONFIG_TABLE_NAME,
                [SetupConstantsInterface::FIELD_PRODUCT_ID, SetupConstantsInterface::FIELD_STORE_ID],
                AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            [SetupConstantsInterface::FIELD_PRODUCT_ID, SetupConstantsInterface::FIELD_STORE_ID],
            AdapterInterface::INDEX_TYPE_UNIQUE
        );
    }
}
