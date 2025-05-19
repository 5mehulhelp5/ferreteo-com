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
 * Class SplitUseConfigParamsField
 */
class SplitUseConfigParamsField implements OperationInterface
{
    const USE_CONFIG_PREFIX = 'use_config_';

    /**
     * @inheritDoc
     */
    public function execute(SchemaSetupInterface $setup)
    {
        $baseTable = $setup->getTable(SetupConstantsInterface::PRODUCT_PUQ_CONFIG_TABLE_NAME);
        $connection = $setup->getConnection();

        $this->addUseConfigFields($baseTable, $connection);
        $this->seedUseConfigFields($baseTable, $connection);
        $this->deleteUseConfigParamsField($baseTable, $connection);
    }

    /**
     * @param string $baseTable
     * @param AdapterInterface $connection
     */
    private function addUseConfigFields($baseTable, AdapterInterface $connection)
    {
        $fieldNames = [
            SetupConstantsInterface::FIELD_REPLACE_QTY,
            SetupConstantsInterface::FIELD_QTY_TYPE,
            SetupConstantsInterface::FIELD_USE_QUANTITIES,
            SetupConstantsInterface::FIELD_START_QTY,
            SetupConstantsInterface::FIELD_QTY_INCREMENT,
            SetupConstantsInterface::FIELD_END_QTY,
            SetupConstantsInterface::FIELD_ALLOW_UNITS,
            SetupConstantsInterface::FIELD_PRICE_PER,
            SetupConstantsInterface::FIELD_PRICE_PER_DIVIDER
        ];

        foreach ($fieldNames as $fieldName) {
            $useConfigFieldName = $this->getUseConfigFieldName($fieldName);
            $connection->addColumn(
                $baseTable,
                $useConfigFieldName,
                ['type'=> Table::TYPE_BOOLEAN, 'nullable' => false, 'default' => 0, 'comment' => $useConfigFieldName]
            );
        }
    }

    /**
     * @param string $fieldName
     * @return string
     */
    private function getUseConfigFieldName($fieldName)
    {
        return SetupConstantsInterface::USE_CONFIG_PREFIX . $fieldName;
    }

    /**
     * @param string $baseTable
     * @param AdapterInterface $connection
     */
    private function seedUseConfigFields($baseTable, AdapterInterface $connection)
    {
        $result = $connection
            ->select()
            ->from($baseTable, [
                SetupConstantsInterface::FIELD_ITEM_ID,
                SetupConstantsInterface::USE_CONFIG_PARAMS_FIELD_NAME])
            //todo: how to escape column name?
            ->where(SetupConstantsInterface::USE_CONFIG_PARAMS_FIELD_NAME, '> ""')
            ->query();

        foreach ($result as $row) {
            $this->updateRowValues($baseTable, $connection, $row);
        }
    }

    /**
     * @param string $baseTable
     * @param AdapterInterface $connection
     * @param array $row
     */
    private function updateRowValues($baseTable, AdapterInterface $connection, $row)
    {
        $columnValues = $this->getColumnsValuesByUseConfigParamsField(
            $row[SetupConstantsInterface::FIELD_USE_CONFIG_PARAMS]
        );

        $rowId = $row[SetupConstantsInterface::FIELD_ITEM_ID];

        $this->updateRow($baseTable, $connection, $rowId, $columnValues);
    }

    /**
     * @param string $useConfigParams
     * @return array
     */
    private function getColumnsValuesByUseConfigParamsField($useConfigParams)
    {
        $chunks = explode(',', $useConfigParams);

        $columnNames = array_map([$this, 'getUseConfigKey'], $chunks);

        $columnValues = array_fill_keys($columnNames, true);

        return $columnValues;
    }

    /**
     * @param string $baseTable
     * @param AdapterInterface $connection
     * @param int $rowId
     * @param array $columnValues
     */
    private function updateRow($baseTable, AdapterInterface $connection, $rowId, $columnValues)
    {
        $where = [SetupConstantsInterface::FIELD_ITEM_ID . ' = ? ' => $rowId];
        $connection->update($baseTable, $columnValues, $where);
    }

    /**
     * @param string $baseTable
     * @param AdapterInterface $connection
     */
    private function deleteUseConfigParamsField($baseTable, AdapterInterface $connection)
    {
        $connection->dropColumn($baseTable, SetupConstantsInterface::USE_CONFIG_PARAMS_FIELD_NAME);
    }

    /**
     * @param string $field
     * @return string
     */
    private function getUseConfigKey($field)
    {
        return SetupConstantsInterface::USE_CONFIG_PREFIX . $field;
    }
}
