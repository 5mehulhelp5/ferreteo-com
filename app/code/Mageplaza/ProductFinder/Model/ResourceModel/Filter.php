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
 * Class Filter
 * @package Mageplaza\ProductFinder\Model\ResourceModel
 */
class Filter extends AbstractDb
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mageplaza_productfinder_filter', 'filter_id');
    }

    /**
     * @param string $ruleId
     *
     * @return array
     * @throws LocalizedException
     */
    public function getFilterByRuleId($ruleId)
    {
        $adapter = $this->getConnection();
        $select  = $adapter->select()->from($this->getMainTable())
            ->where('rule_id = ?', $ruleId);

        return $adapter->fetchAll($select);
    }

    /**
     * @param string $ruleId
     * @param string $option
     *
     * @return array
     * @throws LocalizedException
     */
    public function getFilterIdsByOption($ruleId, $option)
    {
        $ids        = [];
        $filterList = $this->getFilterByRuleId($ruleId);

        foreach ($filterList as $value) {
            $ids[] = $value[$option];
        }

        return $ids;
    }

    /**
     * @param string $filterId
     *
     * @return mixed
     * @throws LocalizedException
     */
    public function getLabelByFilterId($filterId)
    {
        $connect = $this->getConnection();
        $select  = $connect->select()->from($this->getMainTable())->where('filter_id = ?', $filterId);

        return $connect->fetchRow($select)['name'];
    }
}
