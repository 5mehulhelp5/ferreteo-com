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
 * Class Rule
 * @package Mageplaza\ProductFinder\Model\ResourceModel
 */
class Rule extends AbstractDb
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mageplaza_productfinder_rule', 'rule_id');
    }

    /**
     * @param string $ruleId
     *
     * @return array
     * @throws LocalizedException
     */
    public function getRuleById($ruleId)
    {
        $connect = $this->getConnection();
        $select  = $connect->select()->from($this->getMainTable())->where('rule_id = ?', $ruleId);

        return $connect->fetchRow($select);
    }
}
