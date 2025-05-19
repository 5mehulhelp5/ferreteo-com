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

namespace Mageplaza\Faqs\Model\ResourceModel\Article;

use Magento\Sales\Model\ResourceModel\Collection\AbstractCollection;
use Mageplaza\Faqs\Api\Data\SearchResult\ArticleSearchResultInterface;
use Mageplaza\Faqs\Model\Article;

/**
 * Class Collection
 * @package Mageplaza\Faqs\Model\ResourceModel\Article
 */
class Collection extends AbstractCollection implements ArticleSearchResultInterface
{
    protected $_idFieldName = 'article_id';

    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(Article::class, \Mageplaza\Faqs\Model\ResourceModel\Article::class);
    }
}
