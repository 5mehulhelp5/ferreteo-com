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
 * Class ResetEnabled
 * @package Mageplaza\ProductFinder\Model\Config\Source\System
 */
class ResetEnabled implements ArrayInterface
{
    const ALWAYS       = 'always';
    const NO           = 'no';
    const AT_LEAST_ONE = 'at_least_one';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::ALWAYS, 'label' => __('Always Enable')],
            ['value' => self::NO, 'label' => __('No')],
            ['value' => self::AT_LEAST_ONE, 'label' => __('At least a filter is selected')]
        ];
    }
}
