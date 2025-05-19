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
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Registry;
use Magento\Framework\View\LayoutFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;
use Mageplaza\ProductFinder\Controller\Adminhtml\Rule;
use Mageplaza\ProductFinder\Helper\Data;
use Mageplaza\ProductFinder\Model\FilterFactory;
use Mageplaza\ProductFinder\Model\ResourceModel\Filter as ResourceFilter;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule as ResourceRule;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule\CollectionFactory;
use Mageplaza\ProductFinder\Model\RuleFactory;
use Psr\Log\LoggerInterface;

/**
 * Class Save
 * @package Mageplaza\ProductFinder\Controller\Adminhtml\Rule
 */
class Save extends Rule
{
    /**
     * @var FilterFactory
     */
    protected $filterFactory;

    /**
     * @var ResourceFilter
     */
    protected $resourceFilter;

    /**
     * Save constructor.
     *
     * @param Context $context
     * @param ForwardFactory $resultForwardFactory
     * @param PageFactory $resultPageFactory
     * @param Registry $coreRegistry
     * @param RuleFactory $ruleFactory
     * @param ResourceRule $resourceRule
     * @param Data $helperData
     * @param LoggerInterface $logger
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param RawFactory $resultRawFactory
     * @param LayoutFactory $layoutFactory
     * @param JsonFactory $resultJsonFactory
     * @param FilterFactory $filterFactory
     * @param ResourceFilter $resourceFilter
     */
    public function __construct(
        Action\Context $context,
        ForwardFactory $resultForwardFactory,
        PageFactory $resultPageFactory,
        Registry $coreRegistry,
        RuleFactory $ruleFactory,
        ResourceRule $resourceRule,
        Data $helperData,
        LoggerInterface $logger,
        Filter $filter,
        CollectionFactory $collectionFactory,
        RawFactory $resultRawFactory,
        LayoutFactory $layoutFactory,
        JsonFactory $resultJsonFactory,
        FilterFactory $filterFactory,
        ResourceFilter $resourceFilter
    ) {
        $this->filterFactory  = $filterFactory;
        $this->resourceFilter = $resourceFilter;
        parent::__construct(
            $context,
            $resultForwardFactory,
            $resultPageFactory,
            $coreRegistry,
            $ruleFactory,
            $resourceRule,
            $helperData,
            $logger,
            $filter,
            $collectionFactory,
            $resultRawFactory,
            $layoutFactory,
            $resultJsonFactory
        );
    }

    /**
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        /** @var Http $request */
        $request = $this->getRequest();
        if ($data = $request->getPostValue('mpproductfinder')) {
            $rule   = $this->initRule();
            $ruleId = $request->getParam('rule_id');
            try {
                if ($ruleId && !$rule->getId()) {
                    $this->messageManager->addErrorMessage(__('This rule no longer exists.'));

                    return $this->_redirect('*/*/');
                }

                $rule->addData($data);
                $this->_resourceRule->save($rule);
                $filterData = $request->getPostValue('filter');
                $this->updateFilterOptions($filterData, $rule->getId());
                $this->messageManager->addSuccessMessage(__('The rule has been saved.'));

                if ($request->getParam('back', false)) {
                    return $this->_redirect(
                        '*/*/edit',
                        ['rule_id' => $rule->getId(), 'mode' => $rule->getMode(), '_current' => true]
                    );
                }
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->_logger->critical($e);

                return $this->_redirect(
                    '*/*/edit',
                    ['rule_id' => $ruleId, 'mode' => $rule->getMode(), '_current' => true]
                );
            }
        }

        return $this->_redirect('*/*/');
    }

    /**
     * @param array $data
     * @param string $ruleId
     *
     * @throws AlreadyExistsException
     */
    protected function updateFilterOptions($data, $ruleId)
    {
        if ($data) {
            foreach ($data as $value) {
                $filter = $this->filterFactory->create();
                $value  += [
                    'rule_id' => $ruleId,
                    'display' => 'dropdown'
                ];
                $filter->setData($value);
                $this->resourceFilter->save($filter);
            }
        }
    }
}
