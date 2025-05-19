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

namespace Mageplaza\Faqs\Model;

use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Mageplaza\Faqs\Api\Data\ArticleInterface;
use Mageplaza\Faqs\Helper\Data;
use Mageplaza\Faqs\Model\ResourceModel\Article as ArticleModel;
use Mageplaza\Faqs\Model\ResourceModel\Category\CollectionFactory as CategoryCollection;

/**
 * Class Article.
 *
 * @method Article setIsChangedCategoryList(bool $flag)
 * @method Article setIsChangedProductList(bool $flag)
 * @method Article setIsProductGrid(bool $flag)
 * @method Article setAffectedCategoryIds(array $ids)
 * @method Article setAffectedProductIds(array $ids)
 * @method Article setProductsIds(array $data)
 * @method Article setCategoriesIds(array $categoryIds)
 * @method array getCategoriesIds()
 * @method array getProductsIds()
 * @method bool getIsProductGrid()
 * @package Mageplaza\Faqs\Model
 */
class Article extends AbstractModel implements ArticleInterface
{
    /**
     * Cache tag
     *
     * @var string
     */
    const CACHE_TAG = 'mageplaza_faqs_article';

    /**
     * Cache tag
     *
     * @var string
     */
    protected $_cacheTag = 'mageplaza_faqs_article';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'mageplaza_faqs_article';

    /**
     * @var string
     */
    protected $_idFieldName = 'article_id';

    /**
     * @var Data
     */
    protected $_helperData;

    protected $categoryCollection;

    /**
     * @var CategoryCollection
     */
    protected $categoryCollectionFactory;

    /**
     * @var ProductCollection
     */
    protected $productCollection;

    /**
     * @var ProductCollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * Article constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param Data $helperData
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param CategoryCollection $categoryCollectionFactory
     * @param ProductCollectionFactory $productCollectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Data $helperData,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        CategoryCollection $categoryCollectionFactory,
        ProductCollectionFactory $productCollectionFactory,
        array $data = []
    ) {
        $this->_helperData               = $helperData;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->productCollectionFactory  = $productCollectionFactory;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(ArticleModel::class);
    }

    /**
     * @inheritdoc
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    public function getCategoryIds()
    {
        if (!$this->hasData('category_ids')) {
            $ids = $this->_getResource()->getCategoryIds($this);
            $this->setData('category_ids', $ids);
        }

        return (array) $this->_getData('category_ids');
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    public function getProductIds()
    {
        if (!$this->hasData('product_ids')) {
            $ids = $this->_getResource()->getProductIds($this);
            $this->setData('product_ids', $ids);
        }

        return (array) $this->_getData('product_ids');
    }

    /**
     * @return bool|string
     */
    public function getUrl()
    {
        return $this->_helperData->getFaqsUrl($this, Data::TYPE_ARTICLE);
    }

    /**
     * Get article short answer
     *
     * @return string
     */
    public function getShortAnswer()
    {
        $limitChar   = ((int) $this->_helperData->getConfigGeneral('question_detail_page/max_char')) ?: 255;
        $shortAnswer = strip_tags(($this->getArticleContent() && $limitChar > 0) ? $this->getArticleContent() : '');
        if (strlen($shortAnswer) > $limitChar) {
            $shortAnswer = mb_substr($shortAnswer, 0, $limitChar, mb_detect_encoding($shortAnswer)) . '...';
        }

        return $shortAnswer;
    }

    /**
     * @return CategoryCollection
     */
    public function getSelectedCategoriesCollection()
    {
        if ($this->categoryCollection === null) {
            $collection = $this->categoryCollectionFactory->create();
            $collection->join(
                $this->getResource()->getTable('mageplaza_faqs_article_category'),
                'main_table.category_id=' . $this->getResource()->getTable('mageplaza_faqs_article_category') .

                '.category_id AND ' . $this->getResource()->getTable('mageplaza_faqs_article_category')
                . '.article_id="' . $this->getId() . '"',
                ['*']
            );
            $this->categoryCollection = $collection;
        }

        return $this->categoryCollection;
    }

    /**
     * @return ProductCollection
     */
    public function getSelectedProductsCollection()
    {
        if ($this->productCollection === null) {
            $collection = $this->productCollectionFactory->create();
            $collection->getSelect()->join(
                $this->getResource()->getTable('mageplaza_faqs_article_product'),
                'e.entity_id=' . $this->getResource()->getTable('mageplaza_faqs_article_product')
                . '.entity_id AND ' . $this->getResource()->getTable('mageplaza_faqs_article_product') . '.article_id='
                . $this->getId(),
                ['*']
            );
            $this->productCollection = $collection;
        }

        return $this->productCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function getArticleId()
    {
        return $this->getData(self::ARTICLE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setArticleId($value)
    {
        return $this->setData(self::ARTICLE_ID, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setName($value)
    {
        return $this->setData(self::NAME, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorName()
    {
        return $this->getData(self::AUTHOR_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setAuthorName($value)
    {
        return $this->setData(self::AUTHOR_NAME, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorEmail()
    {
        return $this->getData(self::AUTHOR_EMAIL);
    }

    /**
     * {@inheritdoc}
     */
    public function setAuthorEmail($value)
    {
        return $this->setData(self::AUTHOR_EMAIL, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * {@inheritdoc}
     */
    public function setStatus($value)
    {
        return $this->setData(self::STATUS, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getVisibility()
    {
        return $this->getData(self::VISIBILITY);
    }

    /**
     * {@inheritdoc}
     */
    public function setVisibility($value)
    {
        return $this->setData(self::VISIBILITY, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getArticleContent()
    {
        return $this->getData(self::ARTICLE_CONTENT);
    }

    /**
     * {@inheritdoc}
     */
    public function setArticleContent($value)
    {
        return $this->setData(self::ARTICLE_CONTENT, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getStoreIds()
    {
        return $this->getData(self::STORE_IDS);
    }

    /**
     * {@inheritdoc}
     */
    public function setStoreIds($value)
    {
        return $this->setData(self::STORE_IDS, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getPositives()
    {
        return $this->getData(self::POSITIVES);
    }

    /**
     * {@inheritdoc}
     */
    public function setPositives($value)
    {
        return $this->setData(self::POSITIVES, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getNegatives()
    {
        return $this->getData(self::NEGATIVES);
    }

    /**
     * {@inheritdoc}
     */
    public function setNegatives($value)
    {
        return $this->setData(self::NEGATIVES, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getViews()
    {
        return $this->getData(self::VIEWS);
    }

    /**
     * {@inheritdoc}
     */
    public function setViews($value)
    {
        return $this->setData(self::VIEWS, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getPosition()
    {
        return $this->getData(self::POSITION);
    }

    /**
     * {@inheritdoc}
     */
    public function setPosition($value)
    {
        return $this->setData(self::POSITION, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getUrlKey()
    {
        return $this->getData(self::URL_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function setUrlKey($value)
    {
        return $this->setData(self::URL_KEY, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getInRss()
    {
        return $this->getData(self::IN_RSS);
    }

    /**
     * {@inheritdoc}
     */
    public function setInRss($value)
    {
        return $this->setData(self::IN_RSS, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getEmailNotify()
    {
        return $this->getData(self::EMAIL_NOTIFY);
    }

    /**
     * {@inheritdoc}
     */
    public function setEmailNotify($value)
    {
        return $this->setData(self::EMAIL_NOTIFY, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getMetaTitle()
    {
        return $this->getData(self::META_TITLE);
    }

    /**
     * {@inheritdoc}
     */
    public function setMetaTitle($value)
    {
        return $this->setData(self::META_TITLE, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getMetaDescription()
    {
        return $this->getData(self::META_DESCRIPTION);
    }

    /**
     * {@inheritdoc}
     */
    public function setMetaDescription($value)
    {
        return $this->setData(self::META_DESCRIPTION, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getMetaKeywords()
    {
        return $this->getData(self::META_KEYWORDS);
    }

    /**
     * {@inheritdoc}
     */
    public function setMetaKeywords($value)
    {
        return $this->setData(self::META_KEYWORDS, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getMetaRobots()
    {
        return $this->getData(self::META_ROBOTS);
    }

    /**
     * {@inheritdoc}
     */
    public function setMetaRobots($value)
    {
        return $this->setData(self::META_ROBOTS, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * {@inheritdoc}
     */
    public function setUpdatedAt($value)
    {
        return $this->setData(self::UPDATED_AT, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt($value)
    {
        return $this->setData(self::CREATED_AT, $value);
    }

    /**
     * @inheritDoc
     */
    public function setListProductIds($value)
    {
        return $this->setData('list_product_ids', $value);
    }

    /**
     * @inheritDoc
     */
    public function setListCategoryIds($value)
    {
        return $this->setData('list_category_ids', $value);
    }

    /**
     * @inheritDoc
     */
    public function getListProductIds()
    {
        if (!$this->hasData('list_product_ids')) {
            return implode(',', $this->getProductIds());
        }

        return $this->getData('list_product_ids');
    }

    /**
     * @inheritDoc
     */
    public function getListCategoryIds()
    {
        if (!$this->hasData('list_category_ids')) {
            return implode(',', $this->getCategoryIds());
        }

        return $this->getData('list_category_ids');
    }
}
