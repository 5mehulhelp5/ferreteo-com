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
 * @package     Mageplaza_ProductFinder
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\ProductFinder\Controller\Adminhtml\Rule;

use Magento\Backend\Model\View\Result\Forward;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Mageplaza\ProductFinder\Controller\Adminhtml\Rule;

/**
 * Class NewAction
 * @package Mageplaza\ProductFinder\Controller\Adminhtml\Rule
 */
class NewAction extends Rule
{
    /**
     * @return Forward|ResponseInterface|ResultInterface
     */
    public function execute()
    {
        return $this->_resultForwardFactory->create()->forward('edit');
    }
}
