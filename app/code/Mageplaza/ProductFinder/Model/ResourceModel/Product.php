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

namespace Mageplaza\ProductFinder\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Product
 * @package Mageplaza\ProductFinder\Model\ResourceModel
 */
class Product extends AbstractDb
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mageplaza_productfinder_filter_product', 'product_id');
    }

    /**
     * @param array $data
     *
     * @throws LocalizedException
     */
    public function addImportProduct($data)
    {
        $this->getConnection()->insertMultiple($this->getMainTable(), $data);
    }

    /**
     * @param string $optionId
     * @param string $tableEav
     *
     * @return mixed
     * @throws LocalizedException
     */
    public function getLabelAutoRule($optionId, $tableEav)
    {
        $connect = $this->getConnection();
        $select  = $connect->select()
            ->from($this->getMainTable())
            ->join(
                ['eav_table' => $tableEav],
                'filter_options = eav_table.option_id'
            )
            ->where('eav_table.option_id = ?', $optionId);

        return $connect->fetchRow($select)['value'];
    }

    /**
     * @param $ruleId
     * @param $productSku
     *
     * @return array
     * @throws LocalizedException
     */
    public function validateProduct($ruleId, $productSku)
    {
        $connect = $this->getConnection();
        $select  = $connect->select()
            ->from($this->getMainTable())
            ->where('rule_id = ?', $ruleId)
            ->where('product_sku = ?', $productSku);

        return $connect->fetchAll($select);
    }
}
