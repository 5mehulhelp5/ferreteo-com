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

use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Mageplaza\ProductFinder\Controller\Adminhtml\Rule;
use Mageplaza\ProductFinder\Model\Rule as RuleModel;

/**
 * Class Edit
 * @package Mageplaza\ProductFinder\Controller\Adminhtml\Rule
 */
class Edit extends Rule
{
    /**
     * @return Page|ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $ruleId = $this->getRequest()->getParam('rule_id');
        /** @var RuleModel $model */
        $model = $this->_ruleFactory->create();

        if ($ruleId && !$model->getId()) {
            $this->_resourceRule->load($model, $ruleId);
            if ($model->getId() !== $ruleId) {
                $this->messageManager->addErrorMessage(__('This rule no longer exists.'));

                return $this->_redirect('*/*');
            }
        }

        $data = $this->_session->getData('mpproductfinder_rule_data', true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('mpproductfinder_rule', $model);
        /** @var Page $resultPage */
        $resultPage = $this->initPage();
        $resultPage->setActiveMenu('Mageplaza_ProductFinder::finder');
        $resultPage->getConfig()
            ->getTitle()
            ->prepend($ruleId ? $model->getName() : __('Create New Product Finder'));

        return $resultPage;
    }
}
