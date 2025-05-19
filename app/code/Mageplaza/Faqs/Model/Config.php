<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the mageplaza.com license that is
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

namespace Mageplaza\Faqs\Model;

use Magento\Framework\DataObject;
use Mageplaza\Faqs\Api\Data\ConfigInterface;

/**
 * Class Config
 * @package Mageplaza\Faqs\Model
 */
class Config extends DataObject implements ConfigInterface
{
    /**
     * {@inheritdoc}
     */
    public function getGeneral()
    {
        return $this->getData(self::GENERAL);
    }

    /**
     * {@inheritdoc}
     */
    public function setGeneral($value)
    {
        $this->setData(self::GENERAL, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFaqHomePage()
    {
        return $this->getData(self::FAQ_HOME_PAGE);
    }

    /**
     * {@inheritdoc}
     */
    public function setFaqHomePage($value)
    {
        $this->setData(self::FAQ_HOME_PAGE, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getProductTab()
    {
        return $this->getData(self::PRODUCT_TAB);
    }

    /**
     * {@inheritdoc}
     */
    public function setProductTab($value)
    {
        $this->setData(self::PRODUCT_TAB, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTermCondition()
    {
        return $this->getData(self::TERM_CONDITION);
    }

    /**
     * {@inheritdoc}
     */
    public function setTermCondition($value)
    {
        $this->setData(self::TERM_CONDITION, $value);

        return $this;
    }
}
