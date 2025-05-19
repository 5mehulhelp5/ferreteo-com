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
 * Class SortBy
 * @package Mageplaza\ProductFinder\Model\Config\Source
 */
class SortBy implements ArrayInterface
{
    const ASC  = 'asc';
    const DESC = 'desc';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::ASC, 'label' => __('Ascending')],
            ['value' => self::DESC, 'label' => __('Descending')]
        ];
    }
}
