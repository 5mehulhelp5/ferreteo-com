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
use Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Products\Grid;
use Mageplaza\ProductFinder\Controller\Adminhtml\Rule;
use Mageplaza\ProductFinder\Helper\Data;
use Mageplaza\ProductFinder\Model\ProductFactory;
use Mageplaza\ProductFinder\Model\ResourceModel\Product as ResourceFilterProduct;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule as ResourceRule;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule\CollectionFactory;
use Mageplaza\ProductFinder\Model\RuleFactory;
use Psr\Log\LoggerInterface;

/**
 * Class AddProduct
 * @package Mageplaza\ProductFinder\Controller\Adminhtml\Rule
 */
class AddProduct extends Rule
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
     * @var ProductModel
     */
    protected $productModel;

    /**
     * @var JsonHelper
     */
    protected $jsonHelper;

    /**
     * AddProduct constructor.
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
     * @param ProductFactory $productFilterFactory
     * @param ResourceFilterProduct $resourceFilterProduct
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
        ProductFactory $productFilterFactory,
        ResourceFilterProduct $resourceFilterProduct,
        ProductModel $productModel,
        JsonHelper $jsonHelper
    ) {
        $this->productFilterFactory  = $productFilterFactory;
        $this->resourceFilterProduct = $resourceFilterProduct;
        $this->productModel          = $productModel;
        $this->jsonHelper            = $jsonHelper;
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
        $result = ['success' => true];
        $data   = $this->getRequest()->getParams();
        $sku    = strtoupper($data['sku']);
        $ruleId = $data['rule_id'];

        if ($productId = $this->productModel->getIdBySku($sku)) {
            $productName = $this->productModel->load($productId)->getName();
            $products    = $this->productFilterFactory->create();
            $filterIds   = [];
            $optionIds   = [];

            if (isset($data['data'])) {
                foreach ($data['data'] as $value) {
                    $filterIds[] = $value['filter_id'];
                    $optionIds[] = $value['option_id'];
                }
            }

            if ($items = $this->resourceFilterProduct->validateProduct($ruleId, $sku)) {
                foreach ($items as $item) {
                    if (!array_diff_assoc(Data::jsonDecode($item['filter_ids']), $filterIds)
                        && !array_diff_assoc(Data::jsonDecode($item['filter_options']), $optionIds)
                    ) {
                        $result['message'] = __(' The product with SKU %1 already exist', $sku);

                        return $this->getResponse()->representJson($this->jsonHelper->jsonEncode($result));
                    }
                }
            }

            $filterData = [
                'rule_id'        => $ruleId,
                'product_name'   => $productName,
                'product_sku'    => $sku,
                'filter_ids'     => Data::jsonEncode($filterIds),
                'filter_options' => Data::jsonEncode($optionIds)
            ];
            $products->setData($filterData);
            $this->resourceFilterProduct->save($products);

            $result['success'] = false;
            $result['html']    = $this->createBlock(
                Grid::class,
                'mpproductfinder.rule.filter.products'
            );
        } else {
            $result['message'] = __(' The product with SKU %1 is invalid!', $sku);
        }

        return $this->getResponse()->representJson($this->jsonHelper->jsonEncode($result));
    }
}
