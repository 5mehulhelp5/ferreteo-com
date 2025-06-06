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

namespace Mageplaza\ProductFinder\Plugin\Router;

use Magento\Catalog\Block\Product\ProductList\Toolbar as CoreToolbar;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Toolbar
 * @package Mageplaza\ProductFinder\Plugin\Router
 */
class Toolbar extends AbstractUrl
{
    /**
     * @param CoreToolbar $subject
     * @param string $result
     * @param array $params
     *
     * @return string
     * @SuppressWarnings(Unused)
     * @throws LocalizedException
     */
    public function afterGetPagerUrl(CoreToolbar $subject, $result, $params = [])
    {
        return $this->createUrl($result, $params);
    }
}
