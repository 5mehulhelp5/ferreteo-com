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

use Exception;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Mageplaza\ProductFinder\Controller\Adminhtml\Rule;

/**
 * Class MassStatus
 * @package Mageplaza\ProductFinder\Controller\Adminhtml\Rule
 */
class MassStatus extends Rule
{
    /**
     * @return Redirect|ResponseInterface|ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        $collection = $this->_filter->getCollection($this->_collectionFactory->create());
        $status     = (int) $this->getRequest()->getParam('status');
        $updated    = 0;
        foreach ($collection as $rule) {
            try {
                $rule->setStatus($status)->save();
                $updated++;
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->messageManager->addErrorMessage(
                    __('Something went wrong while updating status for %1.', $rule->getName())
                );
            }
        }

        if ($updated) {
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been updated.', $updated));
        }

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath('*/*/');
    }
}
