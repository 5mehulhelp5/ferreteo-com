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
 * Class ResetPage
 * @package Mageplaza\ProductFinder\Model\Config\Source\System
 */
class ResetPage implements ArrayInterface
{
    const CURRENT_PAGE = 'current_page';
    const HOME_PAGE    = 'home_page';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::CURRENT_PAGE, 'label' => __('Current Page')],
            ['value' => self::HOME_PAGE, 'label' => __('Home Page')]
        ];
    }
}
