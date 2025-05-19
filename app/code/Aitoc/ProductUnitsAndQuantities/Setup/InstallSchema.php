<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Setup;

use Aitoc\ProductUnitsAndQuantities\Api\Setup\SetupConstantsInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @inheritdoc
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $table = $setup->getConnection()->newTable(
            $setup->getTable(SetupConstantsInterface::PRODUCT_PUQ_CONFIG_TABLE_NAME)
        );

        $columns = [
            [
                'name' => SetupConstantsInterface::FIELD_ITEM_ID,
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'comment' => 'Item Id',
            ],
            [
                'name' => SetupConstantsInterface::FIELD_PRODUCT_ID,
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['unsigned' => true, 'nullable' => false],
                'comment' => 'Product Id'
            ],
            [
                'name' => SetupConstantsInterface::FIELD_REPLACE_QTY,
                'type' => Table::TYPE_TEXT,
                'size' => 255,
                'options' => [],
                'comment' => 'Replace Qty'
            ],
            [
                'name' => SetupConstantsInterface::FIELD_USE_QUANTITIES,
                'type' => Table::TYPE_TEXT,
                'size' => 255,
                'options' => [],
                'comment' => 'Use Quantities'
            ],
            [
                'name' => SetupConstantsInterface::FIELD_ALLOW_UNITS,
                'type' => Table::TYPE_TEXT,
                'size' => 255,
                'options' => [],
                'comment' => 'Allow Units'
            ],
            [
                'name' => SetupConstantsInterface::FIELD_PRICE_PER,
                'type' => Table::TYPE_TEXT,
                'size' => 255,
                'options' => [],
                'comment' => 'Price Per'
            ],
            [
                'name' => SetupConstantsInterface::FIELD_PRICE_PER_DIVIDER,
                'type' => Table::TYPE_TEXT,
                'size' => 255,
                'options' => [],
                'comment' => 'Price Per Divider'
            ]
        ];

        foreach ($columns as $column) {
            $table->addColumn(
                $column['name'],
                $column['type'],
                $column['size'],
                $column['options'],
                $column['comment']
            );
        }

        $table->setComment('Aitoc ProductUnitsAndQuantities main table');

        $setup->getConnection()->createTable($table);
        $setup->endSetup();
    }
}
