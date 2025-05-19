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

namespace Mageplaza\Faqs\Controller\Category;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Forward;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Mageplaza\Faqs\Controller\AjaxSearch;
use Mageplaza\Faqs\Helper\Data;

/**
 * Class View
 * @package Mageplaza\Faqs\Controller\Category
 */
class View extends AjaxSearch
{
    /**
     * @return ResponseInterface|Forward|ResultInterface|Page
     */
    public function execute()
    {
        $id       = $this->getRequest()->getParam('id');
        $category = $this->_helperData->getFactoryByType(Data::TYPE_CATEGORY)->create()->load($id);

        if ($this->getRequest()->isAjax()) {
            return $this->_getAjaxFilterResult();
        }

        if ($category->getEnabled()) {
            return $this->_resultPageFactory->create();
        }

        return $this->_resultForwardFactory->create()->forward('noroute');
    }
}
