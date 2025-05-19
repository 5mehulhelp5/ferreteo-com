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
 * Class DisplayType
 * @package Mageplaza\ProductFinder\Model\Config\Source
 */
class DisplayType implements ArrayInterface
{
    const DROPDOWN    = 'dropdown';
    const SWATCH_TEXT = 'swatch_text';
    const IMAGE_TEXT  = 'image_text';
    const IMAGE       = 'image';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::DROPDOWN, 'label' => __('Dropdown')],
            ['value' => self::SWATCH_TEXT, 'label' => __('Text-based Swatch')],
            ['value' => self::IMAGE_TEXT, 'label' => __('Image and Text')],
            ['value' => self::IMAGE, 'label' => __('Image')]
        ];
    }
}
