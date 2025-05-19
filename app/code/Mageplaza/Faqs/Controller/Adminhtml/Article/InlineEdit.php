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
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Mageplaza\Faqs\Model\Article;
use Mageplaza\Faqs\Model\ArticleFactory;
use RuntimeException;

/**
 * Class InlineEdit
 * @package Mageplaza\Faqs\Controller\Adminhtml\Article
 */
class InlineEdit extends Action
{
    /**
     * JSON Factory
     *
     * @var JsonFactory
     */
    public $jsonFactory;

    /**
     * Article Factory
     *
     * @var ArticleFactory
     */
    public $articleFactory;

    /**
     * InlineEdit constructor.
     *
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param ArticleFactory $articleFactory
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        ArticleFactory $articleFactory
    ) {
        $this->jsonFactory    = $jsonFactory;
        $this->articleFactory = $articleFactory;

        parent::__construct($context);
    }

    /**
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var Json $resultJson */
        $resultJson   = $this->jsonFactory->create();
        $error        = false;
        $messages     = [];
        $articleItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && !empty($articleItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error'    => true,
            ]);
        }

        $key       = array_keys($articleItems);
        $articleId = !empty($key) ? (int) $key[0] : '';
        /** @var Article $article */
        $article = $this->articleFactory->create()->load($articleId);
        try {
            $articleData = $articleItems[$articleId];
            $article
                ->addData($articleData)
                ->save();
        } catch (LocalizedException $e) {
            $messages[] = $this->getErrorWithArticleId($article, $e->getMessage());
            $error      = true;
        } catch (RuntimeException $e) {
            $messages[] = $this->getErrorWithArticleId($article, $e->getMessage());
            $error      = true;
        } catch (Exception $e) {
            $messages[] = $this->getErrorWithArticleId(
                $article,
                __('Something went wrong while saving the Article.')
            );
            $error      = true;
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error'    => $error
        ]);
    }

    /**
     * Add Article id to error message
     *
     * @param Article $article
     * @param string $errorText
     *
     * @return string
     */
    public function getErrorWithArticleId(Article $article, $errorText)
    {
        return '[Article ID: ' . $article->getId() . '] ' . $errorText;
    }
}
