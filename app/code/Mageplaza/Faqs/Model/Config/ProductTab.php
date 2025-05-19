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
use Mageplaza\Faqs\Api\Data\Config\ProductTabInterface;

/**
 * Class ProductTab
 * @package Mageplaza\Faqs\Model\Config
 */
class ProductTab extends DataObject implements ProductTabInterface
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
    public function getShowName()
    {
        return $this->getData(self::SHOW_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setShowName($value)
    {
        $this->setData(self::SHOW_NAME, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getShowDate()
    {
        return $this->getData(self::SHOW_DATE);
    }

    /**
     * {@inheritdoc}
     */
    public function setShowDate($value)
    {
        $this->setData(self::SHOW_DATE, $value);

        return $this;
    }
}
