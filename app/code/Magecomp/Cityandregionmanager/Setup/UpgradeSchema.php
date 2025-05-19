<?php

namespace Magecomp\Cityandregionmanager\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();

        $installer->getConnection()->addColumn(
            $installer->getTable('magecomp_states'),
            'country_id',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 5,
                'comment' => 'Country Id'
            ]
        );
        $stateTable = $installer->getTable('magecomp_states');
        $cityTable = $installer->getTable('magecomp_cities');
        $zipTable = $installer->getTable('magecomp_zip');
        $installer->getConnection()->addIndex(
                $stateTable,
            $installer->getIdxName(
                $stateTable,
                ['states_name','country_id'],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            ),
            ['states_name','country_id'],
            AdapterInterface::INDEX_TYPE_FULLTEXT
        );
        $installer->getConnection()->addIndex(
                $cityTable,
            $installer->getIdxName(
                $cityTable,
                ['states_name','cities_name'],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            ),
            ['states_name','cities_name'],
            AdapterInterface::INDEX_TYPE_FULLTEXT
        );
        $installer->getConnection()->addIndex(
                $zipTable,
                $installer->getIdxName(
                    $zipTable,
                    ['states_name','cities_name','zip_code'],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['states_name','cities_name','zip_code'],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            );
        $installer->endSetup();
    }
}
