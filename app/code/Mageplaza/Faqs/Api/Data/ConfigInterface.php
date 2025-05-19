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
 * Interface ConfigInterface
 * @package Mageplaza\Faqs\Api\Data
 */
interface ConfigInterface
{
    const GENERAL        = 'general';
    const FAQ_HOME_PAGE  = 'faq_home_page';
    const PRODUCT_TAB    = 'product_tab';
    const TERM_CONDITION = 'terms_condition';

    /**
     * @return \Mageplaza\Faqs\Api\Data\Config\GeneralInterface
     */
    public function getGeneral();

    /**
     * @param \Mageplaza\Faqs\Api\Data\Config\GeneralInterface $value
     *
     * @return $this
     */
    public function setGeneral($value);

    /**
     * @return \Mageplaza\Faqs\Api\Data\Config\FaqHomePageInterface
     */
    public function getFaqHomePage();

    /**
     * @param \Mageplaza\Faqs\Api\Data\Config\FaqHomePageInterface $value
     *
     * @return $this
     */
    public function setFaqHomePage($value);

    /**
     * @return \Mageplaza\Faqs\Api\Data\Config\ProductTabInterface
     */
    public function getProductTab();

    /**
     * @param \Mageplaza\Faqs\Api\Data\Config\ProductTabInterface $value
     *
     * @return $this
     */
    public function setProductTab($value);

    /**
     * @return \Mageplaza\Faqs\Api\Data\Config\TermConditionInterface
     */
    public function getTermCondition();

    /**
     * @param \Mageplaza\Faqs\Api\Data\Config\TermConditionInterface $value
     *
     * @return $this
     */
    public function setTermCondition($value);
}
