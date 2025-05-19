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

use Aitoc\ProductUnitsAndQuantities\Setup\Operations\AddFieldsForDynamicQtyRange;
use Aitoc\ProductUnitsAndQuantities\Setup\Operations\AddIsQtyDecimalWithUseConfigFields;
use Aitoc\ProductUnitsAndQuantities\Setup\Operations\AddQtyTypeField;
use Aitoc\ProductUnitsAndQuantities\Setup\Operations\AddUseConfigParamsField;
use Aitoc\ProductUnitsAndQuantities\Setup\Operations\ChangeColumnTypesInBaseTable;
use Aitoc\ProductUnitsAndQuantities\Setup\Operations\CreateOrderItemUnitsTable;
use Aitoc\ProductUnitsAndQuantities\Setup\Operations\SplitUseConfigParamsField;
use Aitoc\ProductUnitsAndQuantities\Setup\Operations\AddStoreIdField;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Zend_Db_Exception;
use Aitoc\ProductUnitsAndQuantities\Setup\Operations\AddUniqueIndex;

/**
 * Class UpgradeSchema
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @var
     */
    private $createOrderItemUnitsTable;

    /**
     * @var AddQtyTypeField
     */
    private $addQtyTypeField;

    /**
     * @var AddFieldsForDynamicQtyRange
     */
    private $addFieldsForDynamicQtyRange;

    /**
     * @var AddUseConfigParamsField
     */
    private $addUseConfigParamsField;

    /**
     * @var ChangeColumnTypesInBaseTable
     */
    private $changeColumnTypesInBaseTable;

    /**
     * @var SplitUseConfigParamsField
     */
    private $splitUseConfigParamsField;

    /**
     * @var AddIsQtyDecimalWithUseConfigFields
     */
    private $addIsQtyDecimalField;

    /**
     * @var AddStoreIdField
     */
    private $addStoreIdField;

    /**
     * @var AddUniqueIndex
     */
    private $addUniqueIndex;

    /**
     * UpgradeSchema constructor.
     *
     * @param CreateOrderItemUnitsTable $createOrderItemUnitsTable
     * @param AddQtyTypeField $addQtyTypeField
     * @param AddFieldsForDynamicQtyRange $addFieldsForDynamicQtyRange
     * @param AddUseConfigParamsField $addUseConfigParamsField
     * @param ChangeColumnTypesInBaseTable $changeColumnTypesInBaseTable
     * @param SplitUseConfigParamsField $splitUseConfigParamsField
     * @param AddIsQtyDecimalWithUseConfigFields $addIsQtyDecimalField
     * @param AddStoreIdField $addStoreId
     * @param AddUniqueIndex $addUniqueIndex
     */
    public function __construct(
        CreateOrderItemUnitsTable $createOrderItemUnitsTable,
        AddQtyTypeField $addQtyTypeField,
        AddFieldsForDynamicQtyRange $addFieldsForDynamicQtyRange,
        AddUseConfigParamsField $addUseConfigParamsField,
        ChangeColumnTypesInBaseTable $changeColumnTypesInBaseTable,
        SplitUseConfigParamsField $splitUseConfigParamsField,
        AddIsQtyDecimalWithUseConfigFields $addIsQtyDecimalField,
        AddStoreIdField $addStoreId,
        AddUniqueIndex $addUniqueIndex
    ) {
        $this->createOrderItemUnitsTable = $createOrderItemUnitsTable;
        $this->addQtyTypeField = $addQtyTypeField;
        $this->addFieldsForDynamicQtyRange = $addFieldsForDynamicQtyRange;
        $this->addUseConfigParamsField = $addUseConfigParamsField;
        $this->changeColumnTypesInBaseTable = $changeColumnTypesInBaseTable;
        $this->splitUseConfigParamsField = $splitUseConfigParamsField;
        $this->addIsQtyDecimalField = $addIsQtyDecimalField;
        $this->addStoreIdField = $addStoreId;
        $this->addUniqueIndex = $addUniqueIndex;
    }

    /**
     * @inheritdoc
     * @throws Zend_Db_Exception
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '0.1.1', '<')) {
            $this->createOrderItemUnitsTable($setup);
        }

        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $this->addQtyTypeField($setup);
        }

        if (version_compare($context->getVersion(), '1.0.2', '<')) {
            $this->addFieldsForDynamicQtyRange($setup);
        }

        if (version_compare($context->getVersion(), '1.0.3', '<')) {
            $this->addUseConfigParamsField($setup);
        }

        if (version_compare($context->getVersion(), '1.0.4', '<')) {
            $this->changeColumnTypesInBaseTable($setup);
        }

        if (version_compare($context->getVersion(), '1.1.0', '<')) {
            $this->splitUseConfigParamsField($setup);
            $this->addIsQtyDecimalField($setup);
        }

        if (version_compare($context->getVersion(), '1.2.1', '<')) {
            $this->addStoreIdField($setup);
            $this->addUniqueIndex($setup);
        }

        $setup->endSetup();
    }

    /**
     * Create 'aitoc_product_units_and_quantities_orders' table
     *
     * @param SchemaSetupInterface $setup
     * @throws Zend_Db_Exception
     */
    public function createOrderItemUnitsTable(SchemaSetupInterface $setup)
    {
        $this->createOrderItemUnitsTable->execute($setup);
    }

    /**
     * Add 'qty_type' field to the 'aitoc_product_units_and_quantities' table
     *
     * @param SchemaSetupInterface $setup
     */
    public function addQtyTypeField(SchemaSetupInterface $setup)
    {
        $this->addQtyTypeField->execute($setup);
    }

    /**
     * Add additional fields to the 'aitoc_product_units_and_quantities' table
     * Fields: start_qty, qty_increment, end_qty
     *
     * @param SchemaSetupInterface $setup
     */
    public function addFieldsForDynamicQtyRange(SchemaSetupInterface $setup)
    {
        $this->addFieldsForDynamicQtyRange->execute($setup);
    }

    /**
     * Add additional fields to the 'aitoc_product_units_and_quantities' table
     * Fields: store_id, qty_increment, end_qty
     *
     * @param SchemaSetupInterface $setup
     */
    public function addStoreIdField(SchemaSetupInterface $setup)
    {
        $this->addStoreIdField->execute($setup);
    }


    /**
     * Add 'use_config_params' field to the 'aitoc_product_units_and_quantities' table
     *
     * @param SchemaSetupInterface $setup
     */
    public function addUseConfigParamsField(SchemaSetupInterface $setup)
    {
        $this->addUseConfigParamsField->execute($setup);
    }

    /**
     * Change fields types to valid types on the 'aitoc_product_units_and_quantities' table
     * Fields: replace_qty, allow_units, start_qty, qty_increment, end_qty
     *
     * @param SchemaSetupInterface $setup
     */
    public function changeColumnTypesInBaseTable(SchemaSetupInterface $setup)
    {
        $this->changeColumnTypesInBaseTable->execute($setup);
    }

    /**
     * Change fields types to valid types on the 'aitoc_product_units_and_quantities' table
     * Fields: replace_qty, allow_units, start_qty, qty_increment, end_qty
     *
     * @param SchemaSetupInterface $setup
     */
    private function splitUseConfigParamsField(SchemaSetupInterface $setup)
    {
        $this->splitUseConfigParamsField->execute($setup);
    }

    /**
     * @param SchemaSetupInterface $setup
     */
    private function addIsQtyDecimalField(SchemaSetupInterface $setup)
    {
        $this->addIsQtyDecimalField->execute($setup);
    }

    /**
     * @param SchemaSetupInterface $setup
     */
    private function addUniqueIndex(SchemaSetupInterface $setup)
    {
        $this->addUniqueIndex->execute($setup);
    }
}
