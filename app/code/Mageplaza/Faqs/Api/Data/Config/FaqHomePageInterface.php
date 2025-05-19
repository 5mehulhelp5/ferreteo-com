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

namespace Mageplaza\Faqs\Api\Data\Config;

/**
 * Interface FaqHomePageInterface
 * @package Mageplaza\Faqs\Api\Data\Config
 */
interface FaqHomePageInterface
{
    const ENABLED         = 'enabled';
    const ROUTE           = 'route';
    const LINK            = 'link';
    const TITLE           = 'title';
    const LAYOUT          = 'layout';
    const DESIGN_STYLE    = 'design_style';
    const CATEGORY_COLUMN = 'category_column';
    const QUESTION_STYLE  = 'question_style';
    const LIMIT_QUESTION  = 'limit_question';
    const SEO             = 'seo';

    /**
     * @return bool
     */
    public function getEnabled();

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function setEnabled($value);

    /**
     * @return string/null
     */
    public function getRoute();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setRoute($value);

    /**
     * @return string/null
     */
    public function getLink();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setLink($value);

    /**
     * @return string/null
     */
    public function getTitle();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setTitle($value);

    /**
     * @return string/null
     */
    public function getLayout();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setLayout($value);

    /**
     * @return string/null
     */
    public function getDesignStyle();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setDesignStyle($value);

    /**
     * @return string/null
     */
    public function getCategoryColumn();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setCategoryColumn($value);

    /**
     * @return string/null
     */
    public function getQuestionStyle();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setQuestionStyle($value);

    /**
     * @return string/null
     */
    public function getLimitQuestion();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setLimitQuestion($value);

    /**
     * @return \Mageplaza\Faqs\Api\Data\Config\FaqHomePage\SeoInterface
     */
    public function getSeo();

    /**
     * @param \Mageplaza\Faqs\Api\Data\Config\FaqHomePage\SeoInterface $value
     *
     * @return $this
     */
    public function setSeo($value);
}
