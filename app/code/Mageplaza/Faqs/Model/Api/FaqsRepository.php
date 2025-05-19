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

namespace Mageplaza\Faqs\Model\Api;

use Exception;
use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Store\Model\StoreManagerInterface;
use Magento\User\Model\UserFactory;
use Mageplaza\Faqs\Api\FaqsRepositoryInterface;
use Mageplaza\Faqs\Helper\Data;
use Mageplaza\Faqs\Model\ArticleFactory;
use Mageplaza\Faqs\Model\CategoryFactory;
use Mageplaza\Faqs\Model\Config;
use Mageplaza\Faqs\Model\Config\FaqHomePage\Seo;
use Mageplaza\Faqs\Model\Config\General;
use Mageplaza\Faqs\Model\Config\FaqHomePage;
use Mageplaza\Faqs\Model\Config\General\Question;
use Mageplaza\Faqs\Model\Config\General\QuestionDetailPage;
use Mageplaza\Faqs\Model\Config\General\SearchBox;
use Mageplaza\Faqs\Model\Config\ProductTab;
use Mageplaza\Faqs\Model\Config\Source\Status;
use Mageplaza\Faqs\Model\Config\Source\Visibility;
use Mageplaza\Faqs\Model\Config\TermCondition;
use Mageplaza\Faqs\Model\Filter\Query\Filter;

/**
 * Class FaqsRepository
 * @package Mageplaza\Faqs\Model\Api
 */
class FaqsRepository implements FaqsRepositoryInterface
{
    /**
     * @var Data
     */
    protected $_helperData;

    /**
     * @var CategoryFactory
     */
    private $_categoryFactory;

    /**
     * @var ArticleFactory
     */
    private $_articleFactory;

    /**
     * @var UserContextInterface
     */
    private $_userContext;

    /**
     * @var UserFactory
     */
    private $_userFactory;

    /**
     * @var StoreManagerInterface
     */
    private $_storeManager;

    /**
     * @var Filter
     */
    private $filterQuery;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var General
     */
    private $general;

    /**
     * @var FaqHomePage
     */
    private $faqHomePage;

    /**
     * FaqsRepository constructor.
     *
     * @param Data $helperData
     * @param CategoryFactory $categoryFactory
     * @param ArticleFactory $articleFactory
     * @param UserFactory $userFactory
     * @param UserContextInterface $userContext
     * @param Filter $filterQuery
     * @param StoreManagerInterface $storeManager
     * @param Config $config
     * @param General $general
     * @param FaqHomePage $faqHomePage
     */
    public function __construct(
        Data $helperData,
        CategoryFactory $categoryFactory,
        ArticleFactory $articleFactory,
        UserFactory $userFactory,
        UserContextInterface $userContext,
        Filter $filterQuery,
        StoreManagerInterface $storeManager,
        Config $config,
        General $general,
        FaqHomePage $faqHomePage
    ) {
        $this->_helperData      = $helperData;
        $this->_categoryFactory = $categoryFactory;
        $this->_articleFactory  = $articleFactory;
        $this->_userContext     = $userContext;
        $this->_userFactory     = $userFactory;
        $this->_storeManager    = $storeManager;
        $this->filterQuery      = $filterQuery;
        $this->config           = $config;
        $this->general          = $general;
        $this->faqHomePage      = $faqHomePage;
    }

    /**
     * @inheritDoc
     */
    public function getCategories(SearchCriteriaInterface $searchCriteria)
    {
        $this->checkModule();
        $categoryCollection = $this->_helperData->getCategoryCollection();

        return $this->filterQuery->getResult($searchCriteria, 'category', $categoryCollection);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigs($storeId = null)
    {
        $configData = $this->_helperData->getConfigValue(Data::CONFIG_MODULE_PATH);

        $question           = new Question($configData['general']['question']);
        $questionDetailPage = new QuestionDetailPage($configData['general']['question_detail_page']);
        $searchBox          = new SearchBox($configData['general']['search_box']);

        $seo           = new Seo($configData['faq_home_page']['seo']);
        $productTab    = new ProductTab($configData['product_tab']);
        $termCondition = new TermCondition($configData['term_condition']);
        $general       = $this->general->setData($configData['general']);
        $general->setQuestion($question)->setQuestionDetailPage($questionDetailPage)->setSearchBox($searchBox);
        $faqHomePage = $this->faqHomePage->setData($configData['faq_home_page']);
        $faqHomePage->setSeo($seo);
        $this->config->setGeneral($general)->setFaqHomePage($faqHomePage)
            ->setProductTab($productTab)->setTermCondition($termCondition);

        return $this->config;
    }

    /**
     * @inheritDoc
     */
    public function getCategory($categoryId)
    {
        $this->checkModule();
        $category = $this->_categoryFactory->create()->load($categoryId);
        if (!$category->getId()) {
            return [];
        }

        if (!$category->getEnabled()) {
            throw new \Magento\Framework\Webapi\Exception(__('Category is disabled'), 101);
        }

        return [$category];
    }

    /**
     * @inheritDoc
     */
    public function getArticles(SearchCriteriaInterface $searchCriteria)
    {
        $this->checkModule();
        $articleCollection = $this->_helperData->getArticleCollection();

        $searchResult = $this->filterQuery->getResult($searchCriteria, 'article', $articleCollection);

        return $searchResult;
    }

    /**
     * @inheritDoc
     */
    public function getArticle($articleId)
    {
        $this->checkModule();
        $article = $this->_articleFactory->create()->load($articleId);

        if ($article->getVisibility() !== '1') {
            throw new \Magento\Framework\Webapi\Exception(__('Article is disabled'), 101);
        }

        return [$article];
    }

    /**
     * @inheritDoc
     */
    public function getArticleVisibility(SearchCriteriaInterface $searchCriteria, $status)
    {
        $this->checkModule();
        $articleCollection = $this->_helperData->getArticleApiCollection()->addFieldToFilter('visibility', $status);

        return $this->filterQuery->getResult($searchCriteria, 'article', $articleCollection);
    }

    /**
     * @inheritDoc
     */
    public function createCategories($categories)
    {
        $this->checkModule();
        if (!$categories->hasData('icon')) {
            $categories->setIcon('fa fa-folder-open');
        }
        if (!$categories->hasData('store_ids')) {
            $categories->setStoreIds('0');
        }
        if ($categories->hasData('list_article_ids')) {
            $categories->setArticlesIds(array_flip(explode(',', $categories->getListArticleIds())));
        }
        if ($categories->hasData('category_id')) {
            $categories->unsetData('category_id');
        }

        $this->validateCategory($categories);

        try {
            $categories->save();
        } catch (Exception $e) {
            throw new \Magento\Framework\Webapi\Exception(__('%1', $e->getMessage()), 101);
        }

        return $categories;
    }

    /**
     * @inheritDoc
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function createArticle($articles)
    {
        $this->checkModule();
        $adminData = $this->_userFactory->create()->load($this->_userContext->getUserId());

        if (!$articles->hasData('author_name')) {
            $articles->setAuthorName($adminData->getName());
        }
        if (!$articles->hasData('author_email')) {
            $articles->setAuthorEmail($adminData->getEmail());
        } elseif (!filter_var($articles->getAuthorEmail(), FILTER_VALIDATE_EMAIL)) {
            throw new \Magento\Framework\Webapi\Exception(__('Email has a wrong format'), 101);
        }
        if ($articles->hasData('list_product_ids')) {
            $articles->setData('products_ids', array_flip(explode(',', $articles->getData('list_product_ids'))));
        }
        if ($articles->hasData('list_category_ids')) {
            $articles->setData('categories_ids', explode(',', $articles->getData('list_category_ids')));
        }
        if (!$articles->hasData('position')) {
            $articles->setPosition(0);
        }
        if (!$articles->hasData('email_notify')) {
            $articles->setEmailNotify('0');
        }
        if (!$articles->hasData('visibility')) {
            $articles->setVisibility(Visibility::NEED_APPROVED);
        }
        if (!$articles->hasData('store_ids')) {
            $articles->setStoreIds('0');
        }
        if ($articles->hasData('article_id')) {
            $articles->setStoreIds('0');
        }
        if (!$articles->hasData('name') || empty($articles->getData('name'))) {
            throw new \Magento\Framework\Webapi\Exception(__('Question is empty'), 101);
        }

        $this->validateStoreId($articles);

        try {
            $articles->save();
            if (!empty($articles->getArticleContent())) {
                $this->_helperData->sendEmailToCustomer($articles);
                $this->_helperData->sendEmailToAdmin($articles);
            }

            return $articles;
        } catch (Exception $exception) {
            throw new \Magento\Framework\Webapi\Exception(__('%1', $exception->getMessage()), 101);
        }
    }

    /**
     * @inheritDoc
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function createQuestion($questions)
    {
        $this->checkModule();
        $visibility = ($this->_helperData->getConfigGeneral('question/need_approved'))
            ? Visibility::NEED_APPROVED : Visibility::HIDDEN;

        if (!$questions->hasData('author_name')) {
            $questions->setAuthorName('Guest');
        }
        if (!$questions->hasData('author_email')) {
            $questions->setAuthorEmail('Guest@gmail.com');
        }

        $questions->setVisibility($visibility);

        return $this->createArticle($questions);
    }

    /**
     * @inheritDoc
     */
    public function deleteCategory($categoryId)
    {
        $this->checkModule();
        $category = $this->_categoryFactory->create()->load($categoryId);
        if (!$category->getId()) {
            throw new \Magento\Framework\Webapi\Exception(__('Category Id does not exist'), 101);
        }

        try {
            $category->delete();

            return true;
        } catch (Exception $exception) {
            throw new \Magento\Framework\Webapi\Exception(__('%1', $exception->getMessage()), 101);
        }
    }

    /**
     * @inheritDoc
     */
    public function deleteArticle($articleId)
    {
        $this->checkModule();
        $article = $this->_articleFactory->create()->load($articleId);
        if (!$article->getId()) {
            throw new \Magento\Framework\Webapi\Exception(__('Article Id does not exist'), 101);
        }

        try {
            $article->delete();

            return true;
        } catch (Exception $exception) {
            throw new \Magento\Framework\Webapi\Exception(__('%1', $exception->getMessage()), 101);
        }
    }

    /**
     * @inheritDoc
     */
    public function submitHelpful($articleId, $isHelpful)
    {
        $this->checkModule();
        $article = $this->_articleFactory->create()->load($articleId);
        if (!$article->getId()) {
            throw new \Magento\Framework\Webapi\Exception(__('Article Id does not exist'), 101);
        }

        if ((int)$article->getStatus() !== Status::ANSWERED
            || (int)$article->getVisibility() !== Visibility::PUBLISH
        ) {
            throw new \Magento\Framework\Webapi\Exception(__('You can not vote this Article.'), 101);
        }

        try {
            if ($isHelpful) {
                $article->setPositives($article->getPositives() + 1);
            } else {
                $article->setNegatives($article->getNegatives() + 1);
            }
            $article->save();

            return true;
        } catch (Exception $exception) {
            throw new \Magento\Framework\Webapi\Exception(__('%1', $exception->getMessage()), 101);
        }
    }

    /**
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function checkModule()
    {
        if (!$this->_helperData->isEnabled()) {
            throw new \Magento\Framework\Webapi\Exception(__('Module is disabled'), 101);
        }
    }

    /**
     * @inheritDoc
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function validateCategory($categories)
    {
        if (!$categories->hasData('name') || empty($categories->getData('name'))) {
            throw new \Magento\Framework\Webapi\Exception(__('Name Category is empty'), 101);
        }
        $this->validateStoreId($categories);
    }

    /**
     * @param AbstractModel $object
     *
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function validateStoreId($object)
    {
        if ($object->hasData('store_ids')) {
            $storeIds = explode(',', $object->getData('store_ids'));
            foreach ($storeIds as $storeId) {
                $allStore = $this->_storeManager->getStores();
                if ($storeId !== '0' && !array_key_exists($storeId, $allStore)) {
                    throw new \Magento\Framework\Webapi\Exception(
                        __('There does not exist a store with id of %1', $storeId),
                        101
                    );
                }
            }
        }
    }
}
