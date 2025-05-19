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

namespace Mageplaza\ProductFinder\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule\Collection as RuleCollection;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule\CollectionFactory;

/**
 * Class Widget
 * @package Mageplaza\ProductFinder\Model\Config\Source
 */
class Widget implements ArrayInterface
{
    /**
     * @var CollectionFactory
     */
    protected $ruleCollection;

    /**
     * Widget constructor.
     *
     * @param CollectionFactory $ruleCollection
     */
    public function __construct(CollectionFactory $ruleCollection)
    {
        $this->ruleCollection = $ruleCollection;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        /** @var RuleCollection $collection */
        $collection = $this->ruleCollection->create();
        $collection->addFieldToFilter('status', true);
        $options = [];

        foreach ($collection as $rule) {
            $options[] = [
                'value' => $rule->getId(),
                'label' => __($rule->getName())
            ];
        }

        return $options;
    }
}
