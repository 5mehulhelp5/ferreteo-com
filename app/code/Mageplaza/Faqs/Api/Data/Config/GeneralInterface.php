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
 * Interface GeneralInterface
 * @package Mageplaza\Faqs\Api\Data\Config
 */
interface GeneralInterface
{
    const ENABLED              = 'enabled';
    const FAQ_COLOR            = 'faq_color';
    const IS_SHOW_HELPFUL      = 'is_show_helpful';
    const RATING_RESTRICT      = 'rating_restrict';
    const QUESTION             = 'question';
    const QUESTION_DETAIL_PAGE = 'question_detail_page';
    const SEARCH_BOX           = 'search_box';

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
    public function getFaqColor();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setFaqColor($value);

    /**
     * @return bool
     */
    public function getIsShowHelpful();

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function setIsShowHelpful($value);

    /**
     * @return string/null
     */
    public function getRatingRestrict();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setRatingRestrict($value);

    /**
     * @return \Mageplaza\Faqs\Api\Data\Config\General\QuestionInterface
     */
    public function getQuestion();

    /**
     * @param \Mageplaza\Faqs\Api\Data\Config\General\QuestionInterface $value
     *
     * @return $this
     */
    public function setQuestion($value);

    /**
     * @return \Mageplaza\Faqs\Api\Data\Config\General\QuestionDetailPageInterface
     */
    public function getQuestionDetailPage();

    /**
     * @param \Mageplaza\Faqs\Api\Data\Config\General\QuestionDetailPageInterface $value
     *
     * @return $this
     */
    public function setQuestionDetailPage($value);

    /**
     * @return \Mageplaza\Faqs\Api\Data\Config\General\SearchBoxInterface
     */
    public function getSearchBox();

    /**
     * @param \Mageplaza\Faqs\Api\Data\Config\General\SearchBoxInterface $value
     *
     * @return $this
     */
    public function setSearchBox($value);
}
