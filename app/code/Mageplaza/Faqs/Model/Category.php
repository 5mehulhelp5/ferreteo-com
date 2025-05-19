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

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Mageplaza\Faqs\Api\Data\CategoryInterface;
use Mageplaza\Faqs\Helper\Data;
use Mageplaza\Faqs\Model\ResourceModel\Article\Collection;
use Mageplaza\Faqs\Model\ResourceModel\Article\CollectionFactory as ArticleCollection;

/**
 * Class Category
 *
 * @method Category setArticlesIds(array $data)
 * @method Category setIsChangedArticleList(bool $flag)
 * @method Category setIsArticleGrid(bool $flag)
 * @method Category setAffectedArticleIds(array $ids)
 * @method bool getIsArticleGrid()
 * @method array getArticlesIds()
 * @package Mageplaza\Faqs\Model
 */
class Category extends AbstractModel implements CategoryInterface
{
    /**
     * Cache tag
     *
     * @var string
     */
    const CACHE_TAG = 'mageplaza_faqs_category';

    /**
     * Cache tag
     *
     * @var string
     */
    protected $_cacheTag = 'mageplaza_faqs_category';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'mageplaza_faqs_category';

    /**
     * @var string
     */
    protected $_idFieldName = 'category_id';

    /**
     * @var Data
     */
    protected $_helperData;

    /**
     * @var Collection
     */
    protected $articleCollection;

    /**
     * @var ArticleCollection
     */
    protected $articleCollectionFactory;

    /**
     * Category constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param Data $helperData
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Data $helperData,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        ArticleCollection $articleCollection,
        array $data = []
    ) {
        $this->_helperData              = $helperData;
        $this->articleCollectionFactory = $articleCollection;

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Category::class);
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
    public function getArticleIds()
    {
        if (!$this->hasData('article_ids')) {
            $ids = $this->_getResource()->getArticleIds($this);
            $this->setData('article_ids', $ids);
        }

        return (array) $this->_getData('article_ids');
    }

    /**
     * @return bool|string
     */
    public function getUrl()
    {
        return $this->_helperData->getFaqsUrl($this, Data::TYPE_CATEGORY);
    }

    /**
     * @return Collection
     */
    public function getSelectedArticlesCollection()
    {
        if ($this->articleCollection === null) {
            $collection = $this->articleCollectionFactory->create();
            $collection->join(
                $this->getResource()->getTable('mageplaza_faqs_article_category'),
                'main_table.article_id=' . $this->getResource()->getTable('mageplaza_faqs_article_category') .
                '.article_id AND ' . $this->getResource()->getTable('mageplaza_faqs_article_category') .
                '.category_id="'
                . $this->getId() . '"',
                ['*']
            );
            $this->articleCollection = $collection;
        }

        return $this->articleCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function getCategoryId()
    {
        return $this->getData(self::CATEGORY_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setCategoryId($value)
    {
        return $this->setData(self::CATEGORY_ID, $value);
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
    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($value)
    {
        return $this->setData(self::DESCRIPTION, $value);
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
    public function getIcon()
    {
        return $this->getData(self::ICON);
    }

    /**
     * {@inheritdoc}
     */
    public function setIcon($value)
    {
        return $this->setData(self::ICON, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getEnabled()
    {
        return $this->getData(self::ENABLED);
    }

    /**
     * {@inheritdoc}
     */
    public function setEnabled($value)
    {
        return $this->setData(self::ENABLED, $value);
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
     * {@inheritdoc}
     */
    public function getListArticleIds()
    {
        if (!$this->hasData('list_article_ids')) {
            return implode(',', $this->getArticleIds());
        }

        return $this->getData('list_article_ids');
    }

    /**
     * {@inheritdoc}
     */
    public function setListArticleIds($value)
    {
        return $this->setData('list_article_ids', $value);
    }
}
