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

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Mirasvit\CatalogLabel\Api\Data\NewProductsInterface;
use Magento\Framework\DB\Ddl\Table;

class Upgrade_1_0_5
{
    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public static function upgrade(SchemaSetupInterface $installer, ModuleContextInterface $context)
    {
        $installer->getConnection()->addColumn($installer->getTable('mst_cataloglabel_label_rule_product'),'store_id', array(
            'type'      => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            'nullable'  => false,
            'length'    => 10,
            'after'     => null,
            'comment'   => 'Store Id'
            )
        );   
    }
}