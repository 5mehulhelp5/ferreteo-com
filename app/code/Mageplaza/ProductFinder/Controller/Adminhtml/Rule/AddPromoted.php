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

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Catalog\Model\Product as ProductModel;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\Registry;
use Magento\Framework\View\LayoutFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;
use Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Promoted\Grid;
use Mageplaza\ProductFinder\Controller\Adminhtml\Rule;
use Mageplaza\ProductFinder\Helper\Data;
use Mageplaza\ProductFinder\Model\PromotedFactory;
use Mageplaza\ProductFinder\Model\ResourceModel\Promoted as ResourcePromoted;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule as ResourceRule;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule\CollectionFactory;
use Mageplaza\ProductFinder\Model\RuleFactory;
use Psr\Log\LoggerInterface;

/**
 * Class AddPromoted
 * @package Mageplaza\ProductFinder\Controller\Adminhtml\Rule
 */
class AddPromoted extends Rule
{
    /**
     * @var PromotedFactory
     */
    protected $promotedFactory;

    /**
     * @var ResourcePromoted
     */
    protected $resourcePromoted;

    /**
     * @var ProductModel
     */
    protected $productModel;

    /**
     * @var JsonHelper
     */
    protected $jsonHelper;

    /**
     * AddPromoted constructor.
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
     * @param PromotedFactory $promotedFactory
     * @param ResourcePromoted $resourcePromoted
     * @param ProductModel $productModel
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
        PromotedFactory $promotedFactory,
        ResourcePromoted $resourcePromoted,
        ProductModel $productModel,
        JsonHelper $jsonHelper
    ) {
        $this->promotedFactory  = $promotedFactory;
        $this->resourcePromoted = $resourcePromoted;
        $this->productModel     = $productModel;
        $this->jsonHelper       = $jsonHelper;
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
     * @return ResponseInterface|Raw|ResultInterface
     * @throws AlreadyExistsException
     */
    public function execute()
    {
        $result   = ['success' => true];
        $data     = $this->getRequest()->getParams();
        $checkAdd = $this->addPromoted($data);

        if ($checkAdd) {
            $result['html'] = $this->createBlock(
                Grid::class,
                'mpproductfinder.rule.promoted.products'
            );
        } else {
            $result = ['success' => false];
        }

        return $this->getResponse()->representJson($this->jsonHelper->jsonEncode($result));
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws AlreadyExistsException
     */
    private function addPromoted($data)
    {
        $promoted = $this->promotedFactory->create();
        $sku      = $data['sku'];
        $ruleId   = $data['rule_id'];
        $checkDup = $promoted->getCollection()
            ->addFieldToFilter('rule_id', $ruleId)
            ->addFieldToFilter('product_sku', $sku)->getData();

        if (!$checkDup && $this->productModel->getIdBySku($sku)) {
            $filterData = [
                'rule_id'     => $ruleId,
                'product_sku' => $sku
            ];
            $promoted->setData($filterData);
            $this->resourcePromoted->save($promoted);

            return true;
        }

        return false;
    }
}
