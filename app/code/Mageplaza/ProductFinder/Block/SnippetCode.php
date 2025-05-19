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

use Mageplaza\ProductFinder\Model\ResourceModel\Rule\Collection;

/**
 * Class SnippetCode
 * @package Mageplaza\ProductFinder\Block
 */
class SnippetCode extends Finder
{
    /**
     * @return Collection|Collection|null
     */
    public function getRuleCollection()
    {
        if (!$this->helperData->isEnabled()) {
            return null;
        }

        /** @var Collection $collection */
        $collection = $this->ruleCollection->create();
        $collection->addFieldToFilter('status', true)
            ->addFieldToFilter('rule_id', $this->getData('rule_id'))
            ->addFieldToFilter('categories_ids', ['null' => true])
            ->setOrder('sort_order', 'asc');

        return $collection;
    }
}
