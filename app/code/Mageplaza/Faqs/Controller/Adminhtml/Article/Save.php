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

namespace Mageplaza\Faqs\Controller\Adminhtml\Article;

use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Helper\Js;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Mageplaza\Faqs\Controller\Adminhtml\Article;
use Mageplaza\Faqs\Helper\Data;
use Mageplaza\Faqs\Model\ArticleFactory;
use RuntimeException;

/**
 * Class Save
 * @package Mageplaza\Faqs\Controller\Adminhtml\Article
 */
class Save extends Article
{
    /**
     * JS helper
     *
     * @var Js
     */
    public $jsHelper;

    /**
     * @var DateTime
     */
    public $date;

    /**
     * @var Data
     */
    protected $_helperData;

    /**
     * Save constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param ArticleFactory $articleFactory
     * @param Js $jsHelper
     * @param DateTime $date
     * @param Data $helperData
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ArticleFactory $articleFactory,
        Js $jsHelper,
        DateTime $date,
        Data $helperData
    ) {
        $this->jsHelper    = $jsHelper;
        $this->date        = $date;
        $this->_helperData = $helperData;

        parent::__construct($articleFactory, $registry, $context);
    }

    /**
     * Save data action
     *
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data = $this->getRequest()->getPost('article')) {
            /** @var \Mageplaza\Faqs\Model\Article $article */
            $article = $this->initArticle();

            $this->_prepareData($article, $data);

            $this->_eventManager->dispatch(
                'mageplaza_faqs_article_prepare_save',
                ['post' => $article, 'request' => $this->getRequest()]
            );

            try {
                /** send email to customer when the question is answered */
                if (!empty($article->getArticleContent())) {
                    $this->_helperData->sendEmailToCustomer($article);
                }
                $article->save();

                $this->messageManager->addSuccessMessage(__('The article has been saved.'));
                $this->_getSession()->setData('mageplaza_faqs_article_data', false);

                if ($this->getRequest()->getParam('back')) {
                    $resultRedirect->setPath('mpfaqs/*/edit', ['id' => $article->getId(), '_current' => true]);
                } else {
                    $resultRedirect->setPath('mpfaqs/*/');
                }

                return $resultRedirect;
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Article.'));
            }

            $this->_getSession()->setData('mageplaza_faqs_article_data', $data);

            $resultRedirect->setPath('mpfaqs/*/edit', ['id' => $article->getId(), '_current' => true]);

            return $resultRedirect;
        }

        $resultRedirect->setPath('mpfaqs/*/');

        return $resultRedirect;
    }

    /**
     * Set specific data
     *
     * @param $article
     * @param array $data
     *
     * @return $this
     */
    protected function _prepareData($article, $data = [])
    {
        if (!$article->getCreatedAt()) {
            $data['created_at'] = $this->date->date();
        }

        if (!isset($data['email_notify'])) {
            $data['email_notify'] = 0;
        }

        $data['updated_at']     = $this->date->date();
        $data['categories_ids'] = (isset($data['categories_ids']) && $data['categories_ids']) ? explode(
            ',',
            $data['categories_ids']
        ) : [];
        $article->addData($data);

        $products = $this->getRequest()->getPost('products');
        if (isset($products)) {
            $article->setIsProductGrid(true);
            $article->setProductsIds(
                $this->jsHelper->decodeGridSerializedInput($products)
            );
        }

        return $this;
    }
}
