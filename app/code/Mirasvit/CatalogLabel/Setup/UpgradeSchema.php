<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-cataloglabel
 * @version   1.1.21
 * @copyright Copyright (C) 2021 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\CatalogLabel\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @var \Mirasvit\CatalogLabel\Model\Observer
     */
    private $observer;

    /**
     * UpgradeSchema constructor.
     * @param \Mirasvit\CatalogLabel\Model\Observer $observer
     */
    public function __construct(
        \Mirasvit\CatalogLabel\Model\Observer $observer
    ) {
        $this->observer = $observer;
    }
    /**
     * {@inheritdoc}
     *
     * @param SchemaSetupInterface   $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            $setup->getConnection()
                ->addColumn(
                    $setup->getTable('mst_cataloglabel_label_display'),
                    'view_style',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'unsigned' => false,
                        'nullable' => true,
                        'comment' => 'View Style',
                    ]
                );
            $setup->getConnection()
                ->addColumn(
                    $setup->getTable('mst_cataloglabel_label_display'),
                    'list_style',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'unsigned' => false,
                        'nullable' => true,
                        'comment' => 'List Style',
                    ]
                );
        }

        if (version_compare($context->getVersion(), '1.0.2') < 0) {
            include_once 'Upgrade_1_0_2.php';

            Upgrade_1_0_2::upgrade($installer, $context);
        }

        if (version_compare($context->getVersion(), '1.0.3') < 0) {
            include_once 'Upgrade_1_0_3.php';

            Upgrade_1_0_3::upgrade($installer, $context);
        }

        if (version_compare($context->getVersion(), '1.0.4') < 0) {
            include_once 'Upgrade_1_0_4.php';

            Upgrade_1_0_4::upgrade($installer, $context);
            $this->observer->apply(true);
        }
        if (version_compare($context->getVersion(), '1.0.5') < 0) {
            include_once 'Upgrade_1_0_5.php';

            Upgrade_1_0_5::upgrade($installer, $context);
        }

        $installer->endSetup();
    }
}
