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
 * Interface ProductTabInterface
 * @package Mageplaza\Faqs\Api\Data\Config
 */
interface ProductTabInterface
{
    const ENABLED        = 'enabled';
    const TITLE          = 'title';
    const LIMIT_QUESTION = 'limit_question';
    const DESIGN_STYLE   = 'design_style';
    const QUESTION_STYLE = 'question_style';
    const SHOW_NAME      = 'show_name';
    const SHOW_DATE      = 'show_date';

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
    public function getLimitQuestion();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setLimitQuestion($value);

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
    public function getQuestionStyle();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setQuestionStyle($value);

    /**
     * @return bool/null
     */
    public function getShowName();

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function setShowName($value);

    /**
     * @return bool/null
     */
    public function getShowDate();

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function setShowDate($value);
}
