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
 * Class Promoted
 * @package Mageplaza\ProductFinder\Model\ResourceModel
 */
class Promoted extends AbstractDb
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mageplaza_productfinder_promoted_product', 'promoted_id');
    }

    /**
     * @param array $data
     *
     * @throws LocalizedException
     */
    public function addImportPromoted($data)
    {
        $this->getConnection()->insertMultiple($this->getMainTable(), $data);
    }

    /**
     * @param string $ruleId
     *
     * @throws LocalizedException
     */
    public function deleteOldPromoted($ruleId)
    {
        $this->getConnection()->delete($this->getMainTable(), 'rule_id = ' . $ruleId);
    }
}
