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

use Magento\Framework\Exception\LocalizedException;
use Magento\Swatches\Block\LayeredNavigation\RenderLayered as CoreRenderLayered;

/**
 * Class RenderLayered
 * @package Mageplaza\ProductFinder\Plugin\Router
 */
class RenderLayered extends AbstractUrl
{
    /**
     * @param CoreRenderLayered $subject
     * @param string $result
     * @param string $attributeCode
     * @param string $optionId
     *
     * @return mixed
     * @SuppressWarnings(Unused)
     * @throws LocalizedException
     */
    public function afterBuildUrl(CoreRenderLayered $subject, $result, $attributeCode, $optionId)
    {
        $query = [$attributeCode => $optionId];

        return $this->createUrl($result, $query);
    }
}
