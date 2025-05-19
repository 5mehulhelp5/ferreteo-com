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

namespace Mageplaza\Faqs\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Webapi\Exception;
use Mageplaza\Faqs\Api\Data\ArticleInterface;
use Mageplaza\Faqs\Api\Data\CategoryInterface;
use Mageplaza\Faqs\Api\Data\SearchResult\ArticleSearchResultInterface;
use Mageplaza\Faqs\Api\Data\SearchResult\CategorySearchResultInterface;

/**
 * Class FaqsRepositoryInterface
 * @package Mageplaza\Faqs\Api
 */
interface FaqsRepositoryInterface
{
    /**
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return CategorySearchResultInterface
     * @throws NoSuchEntityException
     * @throws Exception
     */
    public function getCategories(SearchCriteriaInterface $searchCriteria);

    /**
     * @param string $categoryId
     *
     * @return CategoryInterface[]
     * @throws Exception
     */
    public function getCategory($categoryId);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return ArticleSearchResultInterface
     * @throws NoSuchEntityException
     * @throws Exception
     */
    public function getArticles(SearchCriteriaInterface $searchCriteria);

    /**
     * @param string $articleId
     *
     * @return ArticleInterface[]
     * @throws Exception
     */
    public function getArticle($articleId);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @param int $status
     *
     * @return ArticleSearchResultInterface
     * @throws NoSuchEntityException
     * @throws Exception
     */
    public function getArticleVisibility(SearchCriteriaInterface $searchCriteria, $status);

    /**
     * @param CategoryInterface $categories
     *
     * @return CategoryInterface
     * @throws Exception
     */
    public function createCategories($categories);

    /**
     * @param ArticleInterface $articles
     *
     * @return ArticleInterface
     * @throws Exception
     */
    public function createArticle($articles);

    /**
     * @param ArticleInterface $questions
     *
     * @return ArticleInterface
     * @throws Exception
     */
    public function createQuestion($questions);

    /**
     * @param string $categoryId $id
     *
     * @return bool
     * @throws Exception
     */
    public function deleteCategory($categoryId);

    /**
     * @param string $articleId
     *
     * @return bool
     * @throws Exception
     */
    public function deleteArticle($articleId);

    /**
     * @param string|null $storeId
     *
     * @return \Mageplaza\Faqs\Api\Data\ConfigInterface
     * @return mixed
     */
    public function getConfigs($storeId = null);

    /**
     * @param int $articleId
     * @param bool $isHelpful
     *
     * @return bool
     * @throws Exception
     */
    public function submitHelpful($articleId, $isHelpful);
}
