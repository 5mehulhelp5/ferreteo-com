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
 * @category    Mageplaza
 * @package     Mageplaza_ProductFinder
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\ProductFinder\Block;

use Magento\Widget\Block\BlockInterface;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule\Collection;

/**
 * Class Widget
 * @package Mageplaza\ProductFinder\Block
 */
class Widget extends Finder implements BlockInterface
{
    /**
     * @var string
     */
    protected $_template = 'finder.phtml';

    /**
     * @return Collection|null
     */
    public function getRuleCollection()
    {
        if (!$this->helperData->isEnabled()) {
            return null;
        }

        $collection = $this->ruleCollection->create();
        $collection->addFieldToFilter('status', true)
            ->addFieldToFilter('categories_ids', ['null' => true])
            ->setOrder('sort_order', 'asc');

        return $collection;
    }
}
