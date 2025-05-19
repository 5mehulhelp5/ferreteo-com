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

namespace Mageplaza\Faqs\Api\Data;

/**
 * Interface ArticleInterface
 * @package Mageplaza\Faqs\Api\Data
 */
interface ArticleInterface
{
    const ARTICLE_ID       = 'article_id';
    const NAME             = 'name';
    const AUTHOR_NAME      = 'author_name';
    const AUTHOR_EMAIL     = 'author_email';
    const STATUS           = 'status';
    const VISIBILITY       = 'visibility';
    const ARTICLE_CONTENT  = 'article_content';
    const STORE_IDS        = 'store_ids';
    const POSITIVES        = 'positives';
    const NEGATIVES        = 'negatives';
    const VIEWS            = 'views';
    const POSITION         = 'position';
    const URL_KEY          = 'url_key';
    const IN_RSS           = 'in_rss';
    const EMAIL_NOTIFY     = 'email_notify';
    const META_TITLE       = 'meta_title';
    const META_DESCRIPTION = 'meta_description';
    const META_KEYWORDS    = 'meta_keywords';
    const META_ROBOTS      = 'meta_robots';
    const UPDATED_AT       = 'updated_at';
    const CREATED_AT       = 'created_at';

    /**
     * @return int
     */
    public function getArticleId();

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setArticleId($value);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setName($value);

    /**
     * @return string
     */
    public function getAuthorName();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setAuthorName($value);

    /**
     * @return string
     */
    public function getAuthorEmail();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setAuthorEmail($value);

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setStatus($value);

    /**
     * @return string
     */
    public function getVisibility();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setVisibility($value);

    /**
     * @return string
     */
    public function getArticleContent();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setArticleContent($value);

    /**
     * @return string
     */
    public function getStoreIds();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setStoreIds($value);

    /**
     * @return int
     */
    public function getPositives();

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setPositives($value);

    /**
     * @return int
     */
    public function getNegatives();

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setNegatives($value);

    /**
     * @return int
     */
    public function getViews();

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setViews($value);

    /**
     * @return int
     */
    public function getPosition();

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setPosition($value);

    /**
     * @return string
     */
    public function getUrlKey();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUrlKey($value);

    /**
     * @return int
     */
    public function getInRss();

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setInRss($value);

    /**
     * @return int
     */
    public function getEmailNotify();

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setEmailNotify($value);

    /**
     * @return string
     */
    public function getMetaTitle();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setMetaTitle($value);

    /**
     * @return string
     */
    public function getMetaDescription();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setMetaDescription($value);

    /**
     * @return string
     */
    public function getMetaKeywords();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setMetaKeywords($value);

    /**
     * @return string
     */
    public function getMetaRobots();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setMetaRobots($value);

    /**
     * @return string
     */
    public function getUpdatedAt();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUpdatedAt($value);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setCreatedAt($value);

    /**
     * @return string
     */
    public function getListProductIds();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setListProductIds($value);

    /**
     * @return string
     */
    public function getListCategoryIds();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setListCategoryIds($value);

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasData($key);

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function setData($key, $value);

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getData($key);
}
