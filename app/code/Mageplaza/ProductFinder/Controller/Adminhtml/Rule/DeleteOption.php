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
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\Registry;
use Magento\Framework\View\LayoutFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;
use Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Renderer\AddProduct as AddProductBlock;
use Mageplaza\ProductFinder\Controller\Adminhtml\Rule;
use Mageplaza\ProductFinder\Helper\Data;
use Mageplaza\ProductFinder\Model\OptionsFactory;
use Mageplaza\ProductFinder\Model\ResourceModel\Options as ResourceOptions;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule as ResourceRule;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule\CollectionFactory;
use Mageplaza\ProductFinder\Model\RuleFactory;
use Psr\Log\LoggerInterface;

/**
 * Class DeleteOption
 * @package Mageplaza\ProductFinder\Controller\Adminhtml\Rule
 */
class DeleteOption extends Rule
{
    /**
     * @var OptionsFactory
     */
    protected $optionsFactory;

    /**
     * @var ResourceOptions
     */
    protected $resourceOptions;

    /**
     * @var JsonHelper
     */
    protected $jsonHelper;

    /**
     * DeleteOption constructor.
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
     * @param OptionsFactory $optionsFactory
     * @param ResourceOptions $resourceOptions
     * @param JsonHelper $jsonHelper
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
        OptionsFactory $optionsFactory,
        ResourceOptions $resourceOptions,
        JsonHelper $jsonHelper
    ) {
        $this->optionsFactory  = $optionsFactory;
        $this->resourceOptions = $resourceOptions;
        $this->jsonHelper      = $jsonHelper;
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
     * @return ResponseInterface|ResultInterface|void
     * @throws Exception
     */
    public function execute()
    {
        $result   = ['success' => true];
        $options  = $this->optionsFactory->create();
        $optionId = $this->getRequest()->getParam('optionId');
        $this->resourceOptions->load($options, $optionId)->delete($options);

        $result['html'] = $this->createBlock(AddProductBlock::class, 'mpproductfinder.filter.add.product');

        $this->getResponse()->representJson($this->jsonHelper->jsonEncode($result));
    }
}
