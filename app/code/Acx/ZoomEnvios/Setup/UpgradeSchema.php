<?php

/**
 * Acx_ZoomEnvios Magento Extension
 *
 */

namespace Acx\ZoomEnvios\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Upgrade the Catalog module DB scheme
 */
class UpgradeSchema implements UpgradeSchemaInterface {

    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context) {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.1.2') < 0) {
            //add tax_class_id for sales_order_table table
            $setup->getConnection()->addColumn(
                $setup->getTable('quote'),
                'mailbox_account',
                [
                    'type' => 'text',
                    'nullable' => true,
                    'comment' => 'Mailbox Account',
                ]
            );

            $setup->getConnection()->addColumn(
                $setup->getTable('quote'),
                'pickup_office',
                [
                    'type' => 'text',
                    'nullable' => true,
                    'comment' => 'Pickup Office',
                ]
            );

            $setup->getConnection()->addColumn(
                $setup->getTable('sales_order'),
                'mailbox_account',
                [
                    'type' => 'text',
                    'nullable' => true,
                    'comment' => 'Mailbox Account',
                ]
            );

            $setup->getConnection()->addColumn(
                $setup->getTable('sales_order'),
                'pickup_office',
                [
                    'type' => 'text',
                    'nullable' => true,
                    'comment' => 'Pickup Office',
                ]
            );

        }
        $setup->endSetup();
    }

}
