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

namespace Mageplaza\ProductFinder\Model\Grid;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class RuleMode
 * @package Mageplaza\ProductFinder\Model\Grid
 */
class RuleMode implements ArrayInterface
{
    const MODE_MANUAL = 'manual';
    const MODE_AUTO   = 'auto';

    /**
     * @return array
     */
    public function getRuleMode()
    {
        return [
            self::MODE_AUTO   => __('Automatic'),
            self::MODE_MANUAL => __('Manual')
        ];
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::MODE_AUTO, 'label' => __('Auto')],
            ['value' => self::MODE_MANUAL, 'label' => __('Manual')]
        ];
    }
}
