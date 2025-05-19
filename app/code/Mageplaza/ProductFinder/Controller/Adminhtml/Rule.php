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

namespace Mageplaza\ProductFinder\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\LayoutFactory;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;
use Mageplaza\ProductFinder\Helper\Data;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule as ResourceRule;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule\CollectionFactory;
use Mageplaza\ProductFinder\Model\Rule as RuleModel;
use Mageplaza\ProductFinder\Model\RuleFactory;
use Psr\Log\LoggerInterface;

/**
 * Class Rule
 * @package Mageplaza\ProductFinder\Controller\Adminhtml
 */
abstract class Rule extends Action
{
    const ADMIN_RESOURCE = 'Mageplaza_ProductFinder::finder';

    /**
     * @var ForwardFactory
     */
    protected $_resultForwardFactory;

    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * @var RuleFactory
     */
    protected $_ruleFactory;

    /**
     * @var ResourceRule
     */
    protected $_resourceRule;

    /**
     * @var Data
     */
    protected $_helperData;

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var Filter
     */
    protected $_filter;

    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var RawFactory
     */
    protected $_resultRawFactory;

    /**
     * @var LayoutFactory
     */
    protected $_layoutFactory;

    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * Rule constructor.
     *
     * @param Action\Context $context
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
        JsonFactory $resultJsonFactory
    ) {
        $this->_resultForwardFactory = $resultForwardFactory;
        $this->_resultPageFactory    = $resultPageFactory;
        $this->_coreRegistry         = $coreRegistry;
        $this->_ruleFactory          = $ruleFactory;
        $this->_resourceRule         = $resourceRule;
        $this->_helperData           = $helperData;
        $this->_logger               = $logger;
        $this->_filter               = $filter;
        $this->_collectionFactory    = $collectionFactory;
        $this->_resultRawFactory     = $resultRawFactory;
        $this->_layoutFactory        = $layoutFactory;
        $this->_resultJsonFactory    = $resultJsonFactory;
        parent::__construct($context);
    }

    /**
     * @return Page
     */
    protected function initPage()
    {
        /** @var Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();

        return $resultPage;
    }

    /**
     * @return RuleModel
     */
    protected function initRule()
    {
        $rule = $this->_ruleFactory->create();
        if ($objId = $this->getRequest()->getParam('rule_id')) {
            $this->_resourceRule->load($rule, $objId);
        }

        if (!$this->_coreRegistry->registry('mpproductfinder_rule')) {
            $this->_coreRegistry->register('mpproductfinder_rule', $rule);
        }

        return $rule;
    }

    /**
     * @param string $block
     * @param string $name
     *
     * @return string
     */
    protected function createBlock($block, $name)
    {
        return $this->_layoutFactory->create()->createBlock($block, $name)->toHtml();
    }
}
