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

/**
 * Class Position
 * @package Mageplaza\ProductFinder\Model\Config\Source
 */
class Position implements ArrayInterface
{
    const BOTTOM_CONTENT = 'bottom';
    const TOP_CONTENT    = 'top';
    const MAIN_SIDEBAR   = 'sidebar';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::TOP_CONTENT, 'label' => __('Top Content')],
            ['value' => self::BOTTOM_CONTENT, 'label' => __('Bottom Content')],
            ['value' => self::MAIN_SIDEBAR, 'label' => __('Main Sidebar')]
        ];
    }
}
