<?php

namespace Meetanshi\OrderUpload\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * Class UpgradeSchema
 * @package Meetanshi\OrderUpload\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.4') < 0) {
            $installer->getConnection()
                ->addColumn(
                    $installer->getTable('quote'),
                    'order_comment',
                    [
                        'type' => Table::TYPE_TEXT,
                        'default' => '',
                        'nullable' => true,
                        'comment' => 'Order Comment'
                    ]
                );
        }

        $installer->endSetup();
    }
}
