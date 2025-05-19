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

namespace Mageplaza\ProductFinder\Model\Config\Source\System;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class FindEnabled
 * @package Mageplaza\ProductFinder\Model\Config\Source\System
 */
class FindEnabled implements ArrayInterface
{
    const ALWAYS       = 'always';
    const ALL_SELECTED = 'all';
    const AT_LEAST_ONE = 'at_least_one';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::ALWAYS, 'label' => __('Always Enable')],
            ['value' => self::ALL_SELECTED, 'label' => __('All filters are selected')],
            ['value' => self::AT_LEAST_ONE, 'label' => __('At least one filter is selected')]
        ];
    }
}
