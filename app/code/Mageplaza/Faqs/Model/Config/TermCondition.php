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
use Mageplaza\Faqs\Api\Data\Config\TermConditionInterface;

/**
 * Class TermCondition
 * @package Mageplaza\Faqs\Model\Config
 */
class TermCondition extends DataObject implements TermConditionInterface
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
    public function getPopupLabel()
    {
        return $this->getData(self::POPUP_LABEL);
    }

    /**
     * {@inheritdoc}
     */
    public function setPopupLabel($value)
    {
        $this->setData(self::POPUP_LABEL, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        return $this->getData(self::CONTENT);
    }

    /**
     * {@inheritdoc}
     */
    public function setContent($value)
    {
        $this->setData(self::CONTENT, $value);

        return $this;
    }
}
