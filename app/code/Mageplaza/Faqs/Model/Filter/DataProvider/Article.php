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

namespace Mageplaza\Faqs\Model\Filter\DataProvider;

use Magento\Catalog\Api\Data\ProductSearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Mageplaza\Faqs\Helper\Data;
use Mageplaza\Faqs\Model\ResourceModel\Article\Collection;

/**
 * Product field data provider, used for GraphQL resolver processing.
 */
class Article
{
    /**
     * @var ProductSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var Data
     */
    private $helperData;

    /**
     * Post constructor.
     *
     * @param CollectionProcessorInterface $collectionProcessor
     * @param Data $helperData
     * @param ProductSearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        CollectionProcessorInterface $collectionProcessor,
        Data $helperData,
        ProductSearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor  = $collectionProcessor;
        $this->helperData           = $helperData;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @param $collection
     *
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getList(
        SearchCriteriaInterface $searchCriteria,
        $collection
    ) {
        /** @var Collection $collection */
        if (!$collection) {
            $collection = $this->helperData->getArticleCollection();
        }
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());

        return $searchResult;
    }
}
