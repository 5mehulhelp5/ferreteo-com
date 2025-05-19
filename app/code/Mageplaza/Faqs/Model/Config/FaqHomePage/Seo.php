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

namespace Mageplaza\Faqs\Model\Config\FaqHomePage;

use Magento\Framework\DataObject;
use Mageplaza\Faqs\Api\Data\Config\FaqHomePage\SeoInterface;

/**
 * Class Seo
 * @package Mageplaza\Faqs\Model\Config\FaqHomePage
 */
class Seo extends DataObject implements SeoInterface
{
    /**
     * {@inheritdoc}
     */
    public function getMetaTitle()
    {
        return $this->getData(self::META_TITLE);
    }

    /**
     * {@inheritdoc}
     */
    public function setMetaTitle($value)
    {
        $this->setData(self::META_TITLE, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetaDescription()
    {
        return $this->getData(self::META_DESCRIPTION);
    }

    /**
     * {@inheritdoc}
     */
    public function setMetaDescription($value)
    {
        $this->setData(self::META_DESCRIPTION, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetaKeyword()
    {
        return $this->getData(self::META_KEYWORD);
    }

    /**
     * {@inheritdoc}
     */
    public function setMetaKeyword($value)
    {
        $this->setData(self::META_KEYWORD, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetaRobot()
    {
        return $this->getData(self::META_ROBOT);
    }

    /**
     * {@inheritdoc}
     */
    public function setMetaRobot($value)
    {
        $this->setData(self::META_ROBOT, $value);

        return $this;
    }
}
