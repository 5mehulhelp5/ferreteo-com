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

namespace Mageplaza\Faqs\Model\Config\General;

use Magento\Framework\DataObject;
use Mageplaza\Faqs\Api\Data\Config\General\QuestionDetailPageInterface;

/**
 * Class QuestionDetailPage
 * @package Mageplaza\Faqs\Model\Config\General
 */
class QuestionDetailPage extends DataObject implements QuestionDetailPageInterface
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
    public function getMaxChar()
    {
        return $this->getData(self::MAX_CHAR);
    }

    /**
     * {@inheritdoc}
     */
    public function setMaxChar($value)
    {
        $this->setData(self::MAX_CHAR, $value);

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
}
