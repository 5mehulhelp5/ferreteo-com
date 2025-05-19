<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category  Mageplaza
 * @package   Mageplaza_ProductFinder
 * @copyright Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license   https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\ProductFinder\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Zend_Db_Exception;

/**
 * Class InstallSchema
 * @package Mageplaza\ProductFinder\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     *
     * @throws Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('mageplaza_productfinder_rule')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('mageplaza_productfinder_rule'))
                ->addColumn('rule_id', Table::TYPE_INTEGER, null, [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ], 'Rule ID')
                ->addColumn(
                    'name',
                    Table::TYPE_TEXT,
                    255,
                    ['unsigned' => true, 'nullable' => false],
                    'Rule Name'
                )
                ->addColumn(
                    'status',
                    Table::TYPE_INTEGER,
                    1,
                    ['nullable' => false],
                    'Status'
                )
                ->addColumn('template', Table::TYPE_TEXT, 255, [], 'Template')
                ->addColumn('mode', Table::TYPE_TEXT, 255, ['nullable => false'], 'Mode')
                ->addColumn('position', Table::TYPE_TEXT, 255, [], 'Position')
                ->addColumn('result_url', Table::TYPE_TEXT, 255, [], 'Finder Result Page URL')
                ->addColumn('page_title', Table::TYPE_TEXT, 255, [], 'Finder Page Title')
                ->addColumn('categories_ids', Table::TYPE_TEXT, '64k', [], 'Category')
                ->addColumn('sort_order', Table::TYPE_INTEGER, null, [], 'Sort Order')
                ->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                    'Creation Time'
                )
                ->addColumn(
                    'updated_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                    'Update Time'
                )
                ->addIndex(
                    $installer->getIdxName('mageplaza_productfinder_rule', 'result_url'),
                    'result_url',
                    ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
                )
                ->setComment('Product Finder Rule Table');

            $installer->getConnection()->createTable($table);
        }

        if (!$installer->tableExists('mageplaza_productfinder_filter')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('mageplaza_productfinder_filter'))
                ->addColumn('filter_id', Table::TYPE_INTEGER, null, [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ], 'Filter ID')
                ->addColumn(
                    'name',
                    Table::TYPE_TEXT,
                    255,
                    ['unsigned' => true, 'nullable' => false],
                    'Filter Name'
                )
                ->addColumn(
                    'rule_id',
                    Table::TYPE_INTEGER,
                    255,
                    ['unsigned' => true, 'nullable' => false],
                    'Rule ID'
                )
                ->addColumn('sort_by', Table::TYPE_TEXT, 255, [], 'Sort By')
                ->addColumn('display', Table::TYPE_TEXT, 255, [], 'Display')
                ->addColumn(
                    'attribute',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable => false'],
                    'Attribute'
                )
                ->addIndex('rule_id', 'rule_id')
                ->addForeignKey(
                    $installer->getFkName(
                        'mageplaza_productfinder_filter',
                        'rule_id',
                        'mageplaza_productfinder_rule',
                        'rule_id'
                    ),
                    'rule_id',
                    $installer->getTable('mageplaza_productfinder_rule'),
                    'rule_id',
                    Table::ACTION_CASCADE
                )
                ->setComment('Product Finder Filter Table');

            $installer->getConnection()->createTable($table);
        }

        if (!$installer->tableExists('mageplaza_productfinder_filter_options')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('mageplaza_productfinder_filter_options'))
                ->addColumn('option_id', Table::TYPE_INTEGER, null, [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ], 'Option ID')
                ->addColumn(
                    'filter_id',
                    Table::TYPE_INTEGER,
                    255,
                    ['unsigned' => true, 'nullable' => false],
                    'Filter ID'
                )
                ->addColumn('name', Table::TYPE_TEXT, 255, [], 'Option Name')
                ->addColumn('image', Table::TYPE_TEXT, 255, [], 'Image')
                ->addColumn('value', Table::TYPE_TEXT, 255, [], 'Value')
                ->addIndex('filter_id', 'filter_id')
                ->addForeignKey(
                    $installer->getFkName(
                        'mageplaza_productfinder_filter_options',
                        'filter_id',
                        'mageplaza_productfinder_filter',
                        'filter_id'
                    ),
                    'filter_id',
                    $installer->getTable('mageplaza_productfinder_filter'),
                    'filter_id',
                    Table::ACTION_CASCADE
                )
                ->setComment('Product Finder Filter Option Table');

            $installer->getConnection()->createTable($table);
        }

        if (!$installer->tableExists('mageplaza_productfinder_filter_product')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('mageplaza_productfinder_filter_product'))
                ->addColumn('product_id', Table::TYPE_INTEGER, null, [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ], 'Product ID')
                ->addColumn(
                    'rule_id',
                    Table::TYPE_INTEGER,
                    255,
                    ['unsigned' => true, 'nullable' => false],
                    'Rule ID'
                )
                ->addColumn('product_name', Table::TYPE_TEXT, 255, [], 'Product Name')
                ->addColumn('product_sku', Table::TYPE_TEXT, 255, [], 'Product SKU')
                ->addColumn('filter_ids', Table::TYPE_TEXT, '64k', [], 'Filter Ids')
                ->addColumn('filter_options', Table::TYPE_TEXT, '64k', [], 'Filter Options')
                ->addForeignKey(
                    $installer->getFkName(
                        'mageplaza_productfinder_filter_product',
                        'rule_id',
                        'mageplaza_productfinder_rule',
                        'rule_id'
                    ),
                    'rule_id',
                    $installer->getTable('mageplaza_productfinder_rule'),
                    'rule_id',
                    Table::ACTION_CASCADE
                )
                ->setComment('Product Finder Filter Option Product Table');

            $installer->getConnection()->createTable($table);
        }

        if (!$installer->tableExists('mageplaza_productfinder_promoted_product')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('mageplaza_productfinder_promoted_product'))
                ->addColumn('promoted_id', Table::TYPE_INTEGER, null, [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ], 'Entity ID')
                ->addColumn(
                    'rule_id',
                    Table::TYPE_INTEGER,
                    255,
                    ['unsigned' => true, 'nullable' => false],
                    'Rule ID'
                )
                ->addColumn('product_sku', Table::TYPE_TEXT, 255, [], 'Product SKU')
                ->setComment('Product Finder Promoted Product Table');

            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}
