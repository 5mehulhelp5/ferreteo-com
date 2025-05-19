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

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Catalog\Model\Product as ProductModel;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\Registry;
use Magento\Framework\View\LayoutFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;
use Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Promoted\Grid;
use Mageplaza\ProductFinder\Controller\Adminhtml\Rule;
use Mageplaza\ProductFinder\Helper\Data;
use Mageplaza\ProductFinder\Helper\File as HelperFile;
use Mageplaza\ProductFinder\Model\ResourceModel\Promoted as ResourcePromoted;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule as ResourceRule;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule\CollectionFactory;
use Mageplaza\ProductFinder\Model\RuleFactory;
use Psr\Log\LoggerInterface;

/**
 * Class ImportPromoted
 * @package Mageplaza\ProductFinder\Controller\Adminhtml\Import
 */
class ImportPromoted extends Rule
{
    /**
     * @var array
     */
    protected $colData = ['product_sku'];

    /**
     * @var ResourcePromoted
     */
    protected $resourcePromoted;

    /**
     * @var HelperFile
     */
    protected $helperFile;

    /**
     * @var ProductModel
     */
    protected $productModel;

    /**
     * @var JsonHelper
     */
    protected $jsonHelper;

    /**
     * ImportPromoted constructor.
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
     * @param ResourcePromoted $resourcePromoted
     * @param HelperFile $helperFile
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
        ResourcePromoted $resourcePromoted,
        HelperFile $helperFile,
        ProductModel $productModel,
        JsonHelper $jsonHelper
    ) {
        $this->resourcePromoted = $resourcePromoted;
        $this->helperFile       = $helperFile;
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
     * @return ResponseInterface|ResultInterface|void
     * @throws LocalizedException
     */
    public function execute()
    {
        $result = ['success' => false];
        $data   = $this->getRequest()->getParams();
        $this->helperFile->_uploadFile($data, $result);

        if ($data['file']) {
            $dataCsv = $this->helperFile->getData($data['file']);
            if ($dataCsv[0] === $this->colData) {
                if (isset($data['delete'])) {
                    $this->resourcePromoted->deleteOldPromoted($data['rule_id']);
                }
                array_shift($dataCsv);
                $filterData = [];
                foreach ($dataCsv as $item) {
                    if ($this->isValidProduct($item[0]) && $this->checkProductExist($data['rule_id'], $item[0])) {
                        $filterData[] = [
                            'rule_id'     => $data['rule_id'],
                            'product_sku' => $item[0]
                        ];
                    }
                }

                if ($filterData) {
                    $this->resourcePromoted->addImportPromoted($filterData);
                    $result['success'] = true;
                }
            }
        }

        $result['html'] = $this->createBlock(
            Grid::class,
            'mpproductfinder.rule.promoted.products'
        );

        return $this->getResponse()->representJson($this->jsonHelper->jsonEncode($result));
    }

    /**
     * @param string $ruleId
     * @param string $sku
     *
     * @return bool
     * @throws LocalizedException
     */
    public function checkProductExist($ruleId, $sku)
    {
        $resource   = $this->resourcePromoted;
        $connection = $resource->getConnection();
        $select     = $connection->select()->from($resource->getMainTable())->where('rule_id = ?', $ruleId);
        $data       = $connection->fetchAll($select);

        if (!$data) {
            return true;
        }

        if ($this->isValidProduct($sku)) {
            foreach ($data as $value) {
                if (!in_array($sku, $value, true)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function isValidProduct($sku)
    {
        if ($this->productModel->getIdBySku($sku)) {
            return true;
        }

        return false;
    }
}
