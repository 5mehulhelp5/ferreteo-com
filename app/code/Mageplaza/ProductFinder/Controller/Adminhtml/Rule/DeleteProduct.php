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
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\LayoutFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;
use Mageplaza\ProductFinder\Controller\Adminhtml\Rule;
use Mageplaza\ProductFinder\Helper\Data;
use Mageplaza\ProductFinder\Model\Product;
use Mageplaza\ProductFinder\Model\ProductFactory;
use Mageplaza\ProductFinder\Model\ResourceModel\Product as ResourceFilterProduct;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule as ResourceRule;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule\CollectionFactory;
use Mageplaza\ProductFinder\Model\RuleFactory;
use Psr\Log\LoggerInterface;

/**
 * Class DeleteProduct
 * @package Mageplaza\ProductFinder\Controller\Adminhtml\Rule
 */
class DeleteProduct extends Rule
{
    /**
     * @var ProductFactory
     */
    protected $productFilterFactory;

    /**
     * @var ResourceFilterProduct
     */
    protected $resourceFilterProduct;

    /**
     * DeleteProduct constructor.
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
     * @param ProductFactory $productFilterFactory
     * @param ResourceFilterProduct $resourceFilterProduct
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
        ProductFactory $productFilterFactory,
        ResourceFilterProduct $resourceFilterProduct
    ) {
        $this->productFilterFactory  = $productFilterFactory;
        $this->resourceFilterProduct = $resourceFilterProduct;
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
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $param          = $this->getRequest()->getParams();
        $ruleId         = $param['rule_id'];
        $mode           = $param['mode'];

        try {
            /** @var Product $filterProduct */
            $filterProduct = $this->productFilterFactory->create();
            $this->resourceFilterProduct->load($filterProduct, $param['product_id'])->delete($filterProduct);
            $this->messageManager->addSuccessMessage(__('The product has been deleted.'));
        } catch (Exception $e) {
            // display error message
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        $resultRedirect->setPath('*/*/edit', ['rule_id' => $ruleId, 'mode' => $mode]);

        return $resultRedirect;
    }
}
