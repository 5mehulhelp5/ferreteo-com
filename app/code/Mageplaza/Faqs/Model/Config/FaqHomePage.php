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

namespace Mageplaza\Faqs\Model\Config;

use Magento\Framework\DataObject;
use Mageplaza\Faqs\Api\Data\Config\FaqHomePageInterface;

/**
 * Class FaqHomePage
 * @package Mageplaza\Faqs\Model\Config
 */
class FaqHomePage extends DataObject implements FaqHomePageInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEnabled()
    {
        return $this->getData(self::ENABLED);
    }

    /**
     * {@inheritdoc}
     */
    public function setEnabled($value)
    {
        $this->setData(self::ENABLED, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoute()
    {
        return $this->getData(self::ROUTE);
    }

    /**
     * {@inheritdoc}
     */
    public function setRoute($value)
    {
        $this->setData(self::ROUTE, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLink()
    {
        return $this->getData(self::LINK);
    }

    /**
     * {@inheritdoc}
     */
    public function setLink($value)
    {
        $this->setData(self::LINK, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle($value)
    {
        $this->setData(self::TITLE, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLayout()
    {
        return $this->getData(self::LAYOUT);
    }

    /**
     * {@inheritdoc}
     */
    public function setLayout($value)
    {
        $this->setData(self::LAYOUT, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDesignStyle()
    {
        return $this->getData(self::DESIGN_STYLE);
    }

    /**
     * {@inheritdoc}
     */
    public function setDesignStyle($value)
    {
        $this->setData(self::DESIGN_STYLE, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCategoryColumn()
    {
        return $this->getData(self::CATEGORY_COLUMN);
    }

    /**
     * {@inheritdoc}
     */
    public function setCategoryColumn($value)
    {
        $this->setData(self::CATEGORY_COLUMN, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuestionStyle()
    {
        return $this->getData(self::QUESTION_STYLE);
    }

    /**
     * {@inheritdoc}
     */
    public function setQuestionStyle($value)
    {
        $this->setData(self::QUESTION_STYLE, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLimitQuestion()
    {
        return $this->getData(self::LIMIT_QUESTION);
    }

    /**
     * {@inheritdoc}
     */
    public function setLimitQuestion($value)
    {
        $this->setData(self::LIMIT_QUESTION, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSeo()
    {
        return $this->getData(self::SEO);
    }

    /**
     * {@inheritdoc}
     */
    public function setSeo($value)
    {
        $this->setData(self::SEO, $value);

        return $this;
    }
}
