<?php

namespace Ibnab\BankInfo\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        
        $setup->getConnection()->addColumn(
            $setup->getTable('quote_payment'),
            'bank_name',
            [
                'type' => 'text',
                'length' => 245,
                'nullable' => true  ,
                'comment' => 'Bank',
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('quote_payment'),
            'bank_owner',
            [
                'type' => 'text',
                'length' => 245,
                'nullable' => true  ,
                'comment' => 'Bank',
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('sales_order_payment'),
            'bank_name',
            [
                'type' => 'text',
                'length' => 245,
                'nullable' => true  ,
                'comment' => 'Bank',
            ]
        );
        $setup->getConnection()->addColumn(
            $setup->getTable('sales_order_payment'),
            'bank_owner',
            [
                'type' => 'text',
                'nullable' => true  ,
                'length' => 245,
                'comment' => 'Bank',
            ]
        );
        $setup->endSetup();
    }
}