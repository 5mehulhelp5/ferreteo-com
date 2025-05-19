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

namespace Mageplaza\Faqs\Block\Article;

use Magento\Customer\Model\Session;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Messages;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Mageplaza\Faqs\Helper\Data;
use Mageplaza\Faqs\Model\Config\Source\System\AddField;

/**
 * Class Form
 * @package Mageplaza\Faqs\Block
 */
class Form extends Template
{
    /**
     * @var Data
     */
    public $helperData;

    /**
     * @var string
     */
    protected $_template = 'Mageplaza_Faqs::article/form/question.phtml';

    /**
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * @var Session
     */
    protected $_customerSession;

    /**
     * Form constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param Session $customerSession
     * @param Data $helperData
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Session $customerSession,
        Data $helperData,
        array $data = []
    ) {
        $this->_coreRegistry    = $registry;
        $this->_customerSession = $customerSession;
        $this->helperData       = $helperData;

        parent::__construct($context, $data);
    }

    /**
     * Get custom loading image
     *
     * @return string
     */
    public function getLoadingImage()
    {
        return $this->getViewFileUrl('Mageplaza_Faqs::media/images/icon-loader.gif');
    }

    /**
     * Get custom message html
     *
     * @param $priority
     * @param $message
     *
     * @return string
     */
    public function getMessagesHtml($priority, $message)
    {
        /** @var $messagesBlock Messages */
        $messagesBlock = $this->_layout->createBlock(Messages::class);
        $messagesBlock->{$priority}(__($message));

        return $messagesBlock->toHtml();
    }

    /**
     * Get submit form ajax url
     *
     * @return string
     */
    public function getSubmitAjaxUrl()
    {
        return $this->helperData->getUrl('mpfaqs/form/submit');
    }

    /**
     * Is show question form name field
     *
     * @return mixed
     */
    public function isShowNameField()
    {
        return $this->helperData->getConfigGeneral('question/name_field');
    }

    /**
     * Is question form name field required
     *
     * @return bool
     */
    public function isRequiredNameField()
    {
        return $this->isShowNameField() == AddField::REQUIRED;
    }

    /**
     * Is show question form email field
     *
     * @return mixed
     */
    public function isShowEmailField()
    {
        return $this->helperData->getConfigGeneral('question/email_field');
    }

    /**
     * Is question form email field required
     *
     * @return bool
     */
    public function isRequiredEmailField()
    {
        return $this->isShowEmailField() == AddField::REQUIRED;
    }

    /**
     * Get question form max char ( default 255 )
     *
     * @return string
     */
    public function getQuestionMaxChar()
    {
        return ($this->helperData->getConfigGeneral('question/max_char')) ?: '255';
    }

    /**
     * Is show Email notify checkbox
     *
     * @return mixed
     */
    public function isShowEmailNotify()
    {
        return $this->helperData->getConfigGeneral('question/show_notification');
    }

    /**
     * Check is product tab page & get current product ID
     *
     * @return bool
     */
    public function getCurrentProductId()
    {
        $product = $this->_coreRegistry->registry('current_product');
        if (($product instanceof \Magento\Catalog\Model\Product) && $product->getId()) {
            return $product->getId();
        }

        return null;
    }

    /**
     * Get current logged customer name
     *
     * @return null|string
     */
    public function getLoggedCustomerName()
    {
        if (!$this->helperData->isLoggedIn()) {
            return null;
        }

        $customerData = $this->_customerSession->getCustomer();

        return $customerData->getFirstname() . $customerData->getLastname();
    }

    /**
     * Get current logged customer email
     *
     * @return null|string
     */
    public function getLoggedCustomerEmail()
    {
        if (!$this->helperData->isLoggedIn()) {
            return null;
        }

        $customerData = $this->_customerSession->getCustomer();

        return $customerData->getEmail();
    }
}
