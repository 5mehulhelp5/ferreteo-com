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
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Mageplaza\ProductFinder\Controller\Adminhtml\Rule;
use Mageplaza\ProductFinder\Model\Rule as RuleModel;

/**
 * Class Delete
 * @package Mageplaza\ProductFinder\Controller\Adminhtml\Rule
 */
class Delete extends Rule
{
    /**
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $ruleId         = $this->getRequest()->getParam('rule_id');
        try {
            /** @var RuleModel $rule */
            $rule = $this->_ruleFactory->create();
            $this->_resourceRule->load($rule, $ruleId)->delete($rule);
            $this->messageManager->addSuccessMessage(__('The rule has been deleted.'));
        } catch (Exception $e) {
            // display error message
            $this->messageManager->addErrorMessage($e->getMessage());
            // go back to edit form
            $resultRedirect->setPath('*/*/edit', ['rule_id' => $ruleId]);

            return $resultRedirect;
        }
        $resultRedirect->setPath('*/*/');

        return $resultRedirect;
    }
}
