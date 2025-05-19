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

namespace Mageplaza\Faqs\Controller\Article;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Forward;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Mageplaza\Faqs\Controller\AjaxSearch;

/**
 * Class Index
 * @package Mageplaza\Faqs\Controller\Article
 */
class Index extends AjaxSearch
{
    /**
     * @return ResponseInterface|Forward|ResultInterface|Page
     */
    public function execute()
    {
        $page = $this->_resultPageFactory->create();
        if ($this->getRequest()->isAjax()) {
            return $this->_getAjaxFilterResult();
        }
        if ($this->_helperData->isEnabled()
            && $this->_helperData->getFaqsPageConfig('route')
            && $this->_helperData->isEnabledFaqsPage()) {
            $page->getConfig()->setPageLayout($this->_helperData->getFaqsPageConfig('layout'));

            return $page;
        }

        return $this->_redirect('noroute');
    }
}
