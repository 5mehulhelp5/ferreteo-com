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
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class ChangeColumnTypesInBaseTable
 */
class ChangeColumnTypesInBaseTable implements OperationInterface
{
    /**
     * @inheritDoc
     */
    public function execute(SchemaSetupInterface $setup)
    {
        $baseTable = $setup->getTable(SetupConstantsInterface::PRODUCT_PUQ_CONFIG_TABLE_NAME);
        $connection = $setup->getConnection();

        $this->changeColumnType(
            $connection,
            $baseTable,
            SetupConstantsInterface::FIELD_REPLACE_QTY,
            Table::TYPE_INTEGER
        );

        $this->changeColumnType(
            $connection,
            $baseTable,
            SetupConstantsInterface::FIELD_ALLOW_UNITS,
            Table::TYPE_SMALLINT
        );

        $this->changeColumnType($connection, $baseTable, SetupConstantsInterface::FIELD_START_QTY, Table::TYPE_FLOAT);

        $this->changeColumnType(
            $connection,
            $baseTable,
            SetupConstantsInterface::FIELD_QTY_INCREMENT,
            Table::TYPE_FLOAT
        );

        $this->changeColumnType($connection, $baseTable, SetupConstantsInterface::FIELD_END_QTY, Table::TYPE_FLOAT);
    }

    /**
     * @param AdapterInterface $connection
     * @param string $table
     * @param string $column
     * @param string $type
     */
    private function changeColumnType(AdapterInterface $connection, $table, $column, $type)
    {
        $connection->changeColumn(
            $table,
            $column,
            $column,
            [
                'type' => $type,
            ]
        );
    }
}
