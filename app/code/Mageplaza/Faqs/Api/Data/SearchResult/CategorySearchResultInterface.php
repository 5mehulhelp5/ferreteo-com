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
 * @category    Mageplaza
 * @package     Mageplaza_Faqs
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\Faqs\Api\Data\SearchResult;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface CategorySearchResultInterface
 * @api
 */
interface CategorySearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \Mageplaza\Faqs\Api\Data\CategoryInterface[]
     */
    public function getItems();

    /**
     * @param \Mageplaza\Faqs\Api\Data\CategoryInterface[] $items
     * @return $this
     */
    public function setItems(array $items = null);
}
