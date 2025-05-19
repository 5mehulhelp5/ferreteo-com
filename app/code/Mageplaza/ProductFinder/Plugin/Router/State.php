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
use Magento\LayeredNavigation\Block\Navigation\State as CoreState;

/**
 * Class State
 * @package Mageplaza\ProductFinder\Plugin\Router
 */
class State extends AbstractUrl
{
    /**
     * @param CoreState $subject
     * @param string $result
     *
     * @return mixed
     * @throws LocalizedException
     */
    public function afterGetClearUrl(CoreState $subject, $result)
    {
        $filterState = [];
        foreach ($subject->getActiveFilters() as $item) {
            $filterItem                                = $item->getFilter();
            $filterState[$filterItem->getRequestVar()] = $filterItem->getCleanValue();
        }

        return $this->createUrl($result, $filterState);
    }
}
