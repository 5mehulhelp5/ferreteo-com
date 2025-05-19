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

namespace Mageplaza\ProductFinder\Controller\Adminhtml\Import;

use Exception;
use Magento\Backend\App\Action;
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
use Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Products\Grid;
use Mageplaza\ProductFinder\Controller\Adminhtml\Rule;
use Mageplaza\ProductFinder\Helper\Data;
use Mageplaza\ProductFinder\Helper\File as HelperFile;
use Mageplaza\ProductFinder\Model\ResourceModel\Product as ResourceProduct;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule as ResourceRule;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule\CollectionFactory;
use Mageplaza\ProductFinder\Model\RuleFactory;
use Psr\Log\LoggerInterface;

/**
 * Class ImportProduct
 * @package Mageplaza\ProductFinder\Controller\Adminhtml\Import
 */
class ImportProduct extends Rule
{
    /**
     * @var array
     */
    private $colData = ['product_name', 'product_sku', 'filter_ids', 'filter_options'];

    /**
     * @var ResourceProduct
     */
    protected $resourceProduct;

    /**
     * @var HelperFile
     */
    protected $helperFile;

    /**
     * @var JsonHelper
     */
    protected $jsonHelper;

    /**
     * ImportProduct constructor.
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
     * @param ResourceProduct $resourceProduct
     * @param HelperFile $helperFile
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
        ResourceProduct $resourceProduct,
        HelperFile $helperFile,
        JsonHelper $jsonHelper
    ) {
        $this->resourceProduct = $resourceProduct;
        $this->helperFile      = $helperFile;
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
        $result = ['success' => true];
        $data   = $this->getRequest()->getParams();
        $this->helperFile->_uploadFile($data, $result);

        if ($data['file']) {
            $dataCsv = $this->helperFile->getData($data['file']);

            if ($dataCsv[0] === $this->colData) {
                array_shift($dataCsv);
                $filterData = [];
                try {
                    foreach ($dataCsv as $item) {
                        $filterData[] = [
                            'rule_id'        => $data['rule_id'],
                            'product_name'   => $item[0],
                            'product_sku'    => $item[1],
                            'filter_ids'     => $item[2],
                            'filter_options' => $item[3]
                        ];
                    }
                    $this->resourceProduct->addImportProduct($filterData);
                } catch (Exception $e) {
                    $this->_logger->critical($e->getMessage());
                    $result['message'] = __('Something went wrong, please the log file!');
                    $result['success'] = false;
                }
            }
        }

        $result['html'] = $this->createBlock(
            Grid::class,
            'mpproductfinder.rule.products'
        );

        $this->getResponse()->representJson($this->jsonHelper->jsonEncode($result));
    }
}
