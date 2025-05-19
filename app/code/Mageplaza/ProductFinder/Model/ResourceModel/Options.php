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
 * Class Options
 * @package Mageplaza\ProductFinder\Model\ResourceModel
 */
class Options extends AbstractDb
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mageplaza_productfinder_filter_options', 'option_id');
    }

    /**
     * @param string $filterId
     *
     * @return array
     */
    public function toOptionArray($filterId)
    {
        $options    = [];
        $connection = $this->getConnection();
        $select     = $connection->select()
            ->from(['main' => $this->getTable('mageplaza_productfinder_filter_options')])
            ->where('main.filter_id = ?', $filterId);
        $list       = $connection->fetchAll($select);

        foreach ($list as $item) {
            $options[] = [
                'value' => $item['option_id'],
                'label' => $item['name']
            ];
        }

        return $options;
    }

    /**
     * @param string $optionId
     *
     * @return array
     * @throws LocalizedException
     */
    public function getLabelByOptionId($optionId)
    {
        $connect = $this->getConnection();
        $select  = $connect->select()->from($this->getMainTable(), 'name')->where('option_id = ?', $optionId);

        return $connect->fetchRow($select)['name'];
    }

    /**
     * @param string $optionId
     * @param string $filterIdPair
     *
     * @return bool
     * @throws LocalizedException
     */
    public function checkOptionByFilterId($optionId, $filterIdPair)
    {
        $connect = $this->getConnection();
        $select  = $connect->select()
            ->from($this->getMainTable(), 'filter_id')
            ->where('option_id = ?', $optionId);

        return $filterIdPair === $connect->fetchRow($select)['filter_id'];
    }
}
