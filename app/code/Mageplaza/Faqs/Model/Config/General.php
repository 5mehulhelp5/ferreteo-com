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
use Mageplaza\Faqs\Api\Data\Config\GeneralInterface;

/**
 * Class General
 * @package Mageplaza\Faqs\Model\Config
 */
class General extends DataObject implements GeneralInterface
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
    public function getFaqColor()
    {
        return $this->getData(self::FAQ_COLOR);
    }

    /**
     * {@inheritdoc}
     */
    public function setFaqColor($value)
    {
        $this->setData(self::FAQ_COLOR, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getIsShowHelpful()
    {
        return $this->getData(self::IS_SHOW_HELPFUL);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsShowHelpful($value)
    {
        $this->setData(self::IS_SHOW_HELPFUL, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRatingRestrict()
    {
        return $this->getData(self::RATING_RESTRICT);
    }

    /**
     * {@inheritdoc}
     */
    public function setRatingRestrict($value)
    {
        $this->setData(self::RATING_RESTRICT, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuestion()
    {
        return $this->getData(self::QUESTION);
    }

    /**
     * {@inheritdoc}
     */
    public function setQuestion($value)
    {
        $this->setData(self::QUESTION, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuestionDetailPage()
    {
        return $this->getData(self::QUESTION_DETAIL_PAGE);
    }

    /**
     * {@inheritdoc}
     */
    public function setQuestionDetailPage($value)
    {
        $this->setData(self::QUESTION_DETAIL_PAGE, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSearchBox()
    {
        return $this->getData(self::SEARCH_BOX);
    }

    /**
     * {@inheritdoc}
     */
    public function setSearchBox($value)
    {
        $this->setData(self::SEARCH_BOX, $value);

        return $this;
    }
}
