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
 * Class CreateOrderItemUnitsTable
 */
class CreateOrderItemUnitsTable implements OperationInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @throws \Zend_Db_Exception
     */
    public function execute(SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()->newTable(
            $setup->getTable(SetupConstantsInterface::ORDER_ITEM_PUQ_CONFIG_TABLE_NAME)
        )->addColumn(
            SetupConstantsInterface::FIELD_ITEM_ID,
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Item Id'
        )->addColumn(
            SetupConstantsInterface::FIELD_ORDER_ITEM_ID,
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Product Id'
        )->addColumn(
            SetupConstantsInterface::FIELD_PRICE_PER,
            Table::TYPE_TEXT,
            255,
            [],
            'Price Per'
        )->addColumn(
            SetupConstantsInterface::FIELD_PRICE_PER_DIVIDER,
            Table::TYPE_TEXT,
            255,
            [],
            'Price Per Divider'
        )->setComment(
            'Aitoc ProductUnitsAndQuantities orders table'
        );

        $setup->getConnection()->createTable($table);
    }
}
