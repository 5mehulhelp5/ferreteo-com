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
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Eav\Model\ResourceModel\Entity\Attribute as EavAttribute;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\View\LayoutFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;
use Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Products\Grid;
use Mageplaza\ProductFinder\Controller\Adminhtml\Rule;
use Mageplaza\ProductFinder\Helper\Data;
use Mageplaza\ProductFinder\Model\ResourceModel\Filter as ResourceFilter;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule as ResourceRule;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule\CollectionFactory;
use Mageplaza\ProductFinder\Model\RuleFactory;
use Psr\Log\LoggerInterface;
use Zend_Db_Select;

/**
 * Class GenerateProduct
 * @package Mageplaza\ProductFinder\Controller\Adminhtml\Rule
 */
class GenerateProduct extends Rule
{
    /**
     * @var ResourceFilter
     */
    protected $resourceFilter;

    /**
     * @var ResourceConnection
     */
    protected $connection;

    /**
     * @var EavAttribute
     */
    protected $eavAttribute;

    /**
     * @var ProductMetadataInterface
     */
    protected $productMetaData;

    /**
     * GenerateProduct constructor.
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
     * @param ResourceFilter $resourceFilter
     * @param ResourceConnection $connection
     * @param EavAttribute $eavAttribute
     * @param ProductMetadataInterface $productMetaData
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
        ResourceFilter $resourceFilter,
        ResourceConnection $connection,
        EavAttribute $eavAttribute,
        ProductMetadataInterface $productMetaData
    ) {
        $this->resourceFilter  = $resourceFilter;
        $this->connection      = $connection;
        $this->eavAttribute    = $eavAttribute;
        $this->productMetaData = $productMetaData;
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
     * @throws LocalizedException
     */
    public function execute()
    {
        $ruleId  = $this->getRequest()->getParam('rule_id');
        $connect = $this->connection->getConnection();
        $this->deleteOldProduct($ruleId);

        if ($data = $this->getProductData($ruleId)) {
            $connect->insertMultiple(
                $this->resourceFilter->getTable('mageplaza_productfinder_filter_product'),
                $data
            );
        }
        $resultRaw = $this->_resultRawFactory->create();

        return $resultRaw->setContents(
            $this->createBlock(
                Grid::class,
                'mpproductfinder.rule.filter.products'
            )
        );
    }

    /**
     * @param string $ruleId
     */
    protected function deleteOldProduct($ruleId)
    {
        $connect = $this->connection->getConnection();
        $connect->delete(
            $this->resourceFilter->getTable('mageplaza_productfinder_filter_product'),
            ['rule_id = ' . $ruleId]
        );
    }

    /**
     * @param string $ruleId
     *
     * @return array
     * @throws LocalizedException
     */
    protected function getProductData($ruleId)
    {
        $data    = [];
        $filters = $this->resourceFilter->getFilterByRuleId($ruleId);

        foreach ($filters as $filter) {
            $filterData = $this->selectQuery($filter['attribute']);
            foreach ($filterData as $value) {
                $data[] = [
                    'rule_id'        => $ruleId,
                    'product_name'   => $value['product_name'],
                    'product_sku'    => $value['sku'],
                    'filter_ids'     => $filter['filter_id'],
                    'filter_options' => $value['value']
                ];
            }
        }

        return array_unique($data, 0);
    }

    /**
     * @param ResourceConnection $connection
     *
     * @return mixed
     */
    protected function filterProduct($connection)
    {
        // 2,4: The product has Visibility in Catalog and Catalog, Search
        $select = $connection->select()
            ->from(['main' => $this->resourceFilter->getTable('catalog_product_index_eav')])
            ->reset(Zend_Db_Select::COLUMNS)
            ->columns(['main.entity_id'])
            ->where('main.value IN (2,4) AND main.attribute_id = ?', $this->getAttributeId('visibility'));

        return $connection->fetchCol($select);
    }

    /**
     * @param string $code
     *
     * @return int
     */
    protected function getAttributeId($code)
    {
        return $this->eavAttribute->getIdByCode('catalog_product', $code);
    }

    /**
     * @param string $attributeId
     *
     * @return array
     */
    protected function selectQuery($attributeId)
    {
        $edition = $this->productMetaData->getEdition();
        $pair    = $edition === 'Community'
            ? 'main.entity_id = entity_var_table.entity_id'
            : 'entity_table.row_id = entity_var_table.row_id';

        /** @var ResourceConnection $connection */
        $connection = $this->connection->getConnection();
        $select     = $connection->select()
            ->from(['main' => $this->resourceFilter->getTable('catalog_product_index_eav')])
            ->join(
                ['entity_table' => $this->resourceFilter->getTable('catalog_product_entity')],
                'main.entity_id = entity_table.entity_id',
                ['entity_table.sku']
            )->join(
                ['entity_var_table' => $this->resourceFilter->getTable('catalog_product_entity_varchar')],
                $pair . ' AND entity_var_table.attribute_id = ' . $this->getAttributeId('name'),
                ['product_name' => 'entity_var_table.value']
            )->where('main.attribute_id = ?', $attributeId)
            ->where('main.entity_id IN (?)', $this->filterProduct($connection));

        return $connection->fetchAll($select);
    }
}
