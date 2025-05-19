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

namespace Mageplaza\Faqs\Api\Data\Config\FaqHomePage;

/**
 * Interface SeoInterface
 * @package Mageplaza\Faqs\Api\Data\Config\FaqHomePage
 */
interface SeoInterface
{
    const META_TITLE       = 'meta_title';
    const META_DESCRIPTION = 'meta_description';
    const META_KEYWORD     = 'meta_keyword';
    const META_ROBOT       = 'meta_robot';

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
    public function getMetaKeyword();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setMetaKeyword($value);

    /**
     * @return string
     */
    public function getMetaRobot();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setMetaRobot($value);
}
