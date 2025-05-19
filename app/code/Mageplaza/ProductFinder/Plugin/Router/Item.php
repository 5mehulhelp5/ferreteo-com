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

use Magento\Catalog\Model\Layer\Filter\Item as CoreItem;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Item
 * @package Mageplaza\ProductFinder\Plugin\Router
 */
class Item extends AbstractUrl
{
    /**
     * @param CoreItem $subject
     * @param string $result
     *
     * @return mixed
     * @throws LocalizedException
     */
    public function afterGetUrl(CoreItem $subject, $result)
    {
        $query = [
            $subject->getFilter()->getRequestVar()  => ($this->helperData->isLayeredEnable()
                && $subject->getFilter()->getRequestVar() === 'price') ? 'from-to' : $subject->getValue(),
            // exclude current page from urls
            $this->htmlPagerBlock->getPageVarName() => null,
        ];

        return $this->createUrl($result, $query);
    }

    /**
     * @param CoreItem $subject
     * @param mixed $result
     *
     * @return mixed
     * @throws LocalizedException
     */
    public function afterGetRemoveUrl(CoreItem $subject, $result)
    {
        $query = [$subject->getFilter()->getRequestVar() => $subject->getFilter()->getResetValue()];

        return $this->createUrl($result, $query);
    }
}
