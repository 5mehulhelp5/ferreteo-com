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

namespace Mageplaza\Faqs\Api\Data\Config\General;

/**
 * Interface QuestionInterface
 * @package Mageplaza\Faqs\Api\Data\Config\General
 */
interface QuestionInterface
{
    const ENABLED           = 'enabled';
    const MAX_CHAR          = 'max_char';
    const NAME_FIELD        = 'name_field';
    const EMAIL_FIELD       = 'email_field';
    const SHOW_NOTIFICATION = 'show_notification';

    /**
     * @return string
     */
    public function getEnabled();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setEnabled($value);

    /**
     * @return string
     */
    public function getMaxChar();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setMaxChar($value);

    /**
     * @return string
     */
    public function getNameField();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setNameField($value);

    /**
     * @return string
     */
    public function getEmailField();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setEmailField($value);

    /**
     * @return string
     */
    public function getShowNotification();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setShowNotification($value);
}
