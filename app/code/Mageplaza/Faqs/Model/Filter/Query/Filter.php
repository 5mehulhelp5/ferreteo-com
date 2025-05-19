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

namespace Mageplaza\Faqs\Model\Filter\Query;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Mageplaza\Faqs\Model\Filter\DataProvider\Article;
use Mageplaza\Faqs\Model\Filter\DataProvider\Category;
use Mageplaza\Faqs\Model\Filter\DataProvider\Product;

/**
 * Retrieve filtered product data based off given search criteria in a format that GraphQL can interpret.
 */
class Filter
{

    /**
     * @var Article
     */
    private $articleDataProvider;

    /**
     * @var Category
     */
    private $categoryDataProvider;

    /**
     * @var Product
     */
    private $productDataProvider;

    /**
     * Filter constructor.
     *
     * @param Article $articleDataProvider
     * @param Product $productDataProvider
     * @param Category $categoryDataProvider
     */
    public function __construct(
        Article $articleDataProvider,
        Product $productDataProvider,
        Category $categoryDataProvider
    ) {
        $this->articleDataProvider  = $articleDataProvider;
        $this->categoryDataProvider = $categoryDataProvider;
        $this->productDataProvider  = $productDataProvider;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @param string $type
     * @param $collection
     *
     * @return SearchResultsInterface|mixed
     * @throws NoSuchEntityException
     */
    public function getResult(
        SearchCriteriaInterface $searchCriteria,
        $type = 'article',
        $collection = null
    ) {
        switch ($type) {
            case 'category':
                $list = $this->categoryDataProvider->getList($searchCriteria, $collection);
                break;
            case 'product':
                $list = $this->productDataProvider->getList($searchCriteria, $collection);
                break;
            case 'article':
            default:
                $list = $this->articleDataProvider->getList($searchCriteria, $collection);
                break;
        }

        return $list;
    }
}
