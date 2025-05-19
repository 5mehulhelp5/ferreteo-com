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
 * Interface CategoryInterface
 * @package Mageplaza\Faqs\Api\Data
 */
interface CategoryInterface
{
    /**
     * Constants used as data array keys
     */
    const CATEGORY_ID      = 'category_id';
    const NAME             = 'name';
    const DESCRIPTION      = 'description';
    const STORE_IDS        = 'store_ids';
    const URL_KEY          = 'url_key';
    const ICON             = 'icon';
    const ENABLED          = 'enabled';
    const META_TITLE       = 'meta_title';
    const META_DESCRIPTION = 'meta_description';
    const META_KEYWORDS    = 'meta_keywords';
    const META_ROBOTS      = 'meta_robots';
    const POSITION         = 'position';
    const UPDATED_AT       = 'updated_at';
    const CREATED_AT       = 'created_at';

    /**
     *
     * @return int|null
     */
    public function getCategoryId();

    /**
     *
     * @param int $id
     *
     * @return $this
     */
    public function setCategoryId($id);

    /**
     *
     * @return string/null
     */
    public function getName();

    /**
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name);

    /**
     *
     * @return string/null
     */
    public function getDescription();

    /**
     *
     * @param string $content
     *
     * @return $this
     */
    public function setDescription($content);

    /**
     *
     * @return int/null
     */
    public function getStoreIds();

    /**
     *
     * @param int $storeId
     *
     * @return $this
     */
    public function setStoreIds($storeId);

    /**
     *
     * @return string/null
     */
    public function getIcon();

    /**
     *
     * @param string $content
     *
     * @return $this
     */
    public function setIcon($content);

    /**
     *
     * @return int/null
     */
    public function getEnabled();

    /**
     * @param int $enabled
     *
     * @return $this
     */
    public function setEnabled($enabled);

    /**
     * @return int
     */
    public function getPosition();

    /**
     * @param $position
     *
     * @return $this
     */
    public function setPosition($position);

    /**
     *
     * @return string/null
     */
    public function getUrlKey();

    /**
     *
     * @param string $url
     *
     * @return $this
     */
    public function setUrlKey($url);

    /**
     *
     * @return string/null
     */
    public function getMetaTitle();

    /**
     *
     * @param string $meta
     *
     * @return $this
     */
    public function setMetaTitle($meta);

    /**
     *
     * @return string/null
     */
    public function getMetaDescription();

    /**
     *
     * @param string $meta
     *
     * @return $this
     */
    public function setMetaDescription($meta);

    /**
     *
     * @return string/null
     */
    public function getMetaKeywords();

    /**
     *
     * @param string $meta
     *
     * @return $this
     */
    public function setMetaKeywords($meta);

    /**
     *
     * @return string/null
     */
    public function getMetaRobots();

    /**
     *
     * @param string $meta
     *
     * @return $this
     */
    public function setMetaRobots($meta);

    /**
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * @param string $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * Get Post updated date
     *
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set Post updated date
     *
     * @param string $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt($updatedAt);

    /**
     * @return string
     */
    public function getListArticleIds();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setListArticleIds($value);

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
