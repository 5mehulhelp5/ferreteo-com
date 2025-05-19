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
 * Class AddFieldsForDynamicQtyRange
 */
class AddIsQtyDecimalWithUseConfigFields implements OperationInterface
{
    /**
     * @inheritdoc
     */
    public function execute(SchemaSetupInterface $setup)
    {
        $baseTable = $setup->getTable(SetupConstantsInterface::PRODUCT_PUQ_CONFIG_TABLE_NAME);
        $connection = $setup->getConnection();

        $connection->addColumn(
            $baseTable,
            SetupConstantsInterface::FIELD_IS_QTY_DECIMAL,
            ['type'=> Table::TYPE_BOOLEAN, 'nullable' => false, 'default' => false, 'comment' => 'Is Qty Decimal']
        );

        $connection->addColumn(
            $baseTable,
            'use_config_is_qty_decimal',
            [
                'type'=> Table::TYPE_BOOLEAN,
                'nullable' => false,
                'default' => 0,
                'comment' => 'Use config Is Qty Decimal'
            ]
        );
    }
}
