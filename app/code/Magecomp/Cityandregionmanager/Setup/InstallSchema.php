<?php

namespace Magecomp\Cityandregionmanager\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context){

        $installer = $setup;

        $installer->startSetup();
        $table  = $installer->getConnection()
            ->newTable($installer->getTable('magecomp_states'))
        ->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'auto_increment' => true, 'nullable'=>false, 'primary' => true],
            'ID'
        )->addColumn(
            'states_name',
            Table::TYPE_TEXT,
            255,
            [],
            'States name'
        )->setComment(
            'Magecomp States'
        );
        $setup->getConnection()->createTable($table);

        $table = $setup->getConnection()->newTable(
            $setup->getTable('magecomp_cities')
        )->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'auto_increment' => true, 'nullable'=>false, 'primary' => true],
            'ID'
        )->addColumn(
            'states_name',
            Table::TYPE_TEXT,
            255,
            [],
            'States name'
        )->addColumn(
            'cities_name',
            Table::TYPE_TEXT,
            255,
            [],
            'Cities name'
        )->setComment(
            'Magecomp Cities'
        );
        $setup->getConnection()->createTable($table);

        $table = $setup->getConnection()->newTable(
            $setup->getTable('magecomp_zip')
        )->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'auto_increment' => true, 'nullable'=>false, 'primary' => true],
            'ID'
        )->addColumn(
            'states_name',
            Table::TYPE_TEXT,
            255,
            [],
            'States name'
        )->addColumn(
            'cities_name',
            Table::TYPE_TEXT,
            255,
            [],
            'Cities name'
        )->addColumn(
            'zip_code',
            Table::TYPE_TEXT,
            255,
            [],
            'ZIP code'
        )->setComment(
            'Magecomp ZIP'
        );
        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }
}