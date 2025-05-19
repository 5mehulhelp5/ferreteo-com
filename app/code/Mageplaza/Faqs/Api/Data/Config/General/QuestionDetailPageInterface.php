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
 * Interface QuestionDetailPageInterface
 * @package Mageplaza\Faqs\Api\Data\Config\General
 */
interface QuestionDetailPageInterface
{
    const ENABLED  = 'enabled';
    const MAX_CHAR = 'max_char';
    const LAYOUT   = 'layout';

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
    public function getLayout();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setLayout($value);
}
