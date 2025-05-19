<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_Mpqa
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\Mpqa\Model\ResourceModel\Question\Grid;

use Magento\Framework\Api\Search\SearchResultInterface as SearchInterface;
use Magento\Framework\Search\AggregationInterface;
use Webkul\Mpqa\Model\ResourceModel\Question\Collection as QuestionCollection;
use Magento\Eav\Model\ResourceModel\Entity\Attribute;

class Collection extends QuestionCollection implements SearchInterface
{
    /**
     * @var AggregationInterface
     */
    protected $_aggregations;

    /**
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface $entity
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetch
     * @param \Magento\Framework\Event\ManagerInterface $event
     * @param mixed|null $mainTable
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb $eventPrefix
     * @param mixed $eventObject
     * @param mixed $resourceModel
     * @param string $model
     * @param null $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb|null $resource
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entity,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetch,
        \Magento\Framework\Event\ManagerInterface $event,
        $mainTable,
        $eventPrefix,
        $eventObject,
        $resourceModel,
        $model = \Magento\Framework\View\Element\UiComponent\DataProvider\Document::class,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null,
        Attribute $_eavattribute
    ) {
        
        $this->_eavattribute  = $_eavattribute;
        parent::__construct(
            $entity,
            $logger,
            $fetch,
            $event,
            $connection,
            $resource
        );
        $this->_eventPrefix = $eventPrefix;
        $this->_eventObject = $eventObject;
        $this->_init($model, $resourceModel);
        $this->setMainTable($mainTable);
    }

    /**
     * @return AggregationInterface
     */
    public function getAggregations()
    {
        return $this->_aggregations;
    }

    /**
     * @param AggregationInterface $aggregations
     * @return $this
     */
    public function setAggregations($aggregations)
    {
        $this->_aggregations = $aggregations;
    }

    /**
     * Retrieve all ids for collection
     * Backward compatibility with EAV collection
     *
     * @param int $limitCount
     * @param int $offset
     * @return array
     */
    public function getAllIds($limitCount = null, $offset = null)
    {
        return $this->getConnection()->fetchCol($this->_getAllIdsSelect($limitCount, $offset), $this->_bindParams);
    }

    /**
     * Get search criteria.
     *
     * @return \Magento\Framework\Api\SearchCriteriaInterface|null
     */
    public function getSearchCriteria()
    {
        return null;
    }

    /**
     * Set search criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setSearchCriteria(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null)
    {
        return $this;
    }

    /**
     * Get total count.
     *
     * @return int
     */
    public function getTotalCount()
    {
        return $this->getSize();
    }

    /**
     * Set total count.
     *
     * @param int $totalCount
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setTotalCount($totalCount)
    {
        return $this;
    }

    /**
     * Set items list.
     *
     * @param \Magento\Framework\Api\ExtensibleDataInterface[] $items
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setItems(array $items = null)
    {
        return $this;
    }

    protected function _initSelect()
    {
        $eavAttribute = $this->_eavattribute;
        $proAttrId = $eavAttribute->getIdByCode("catalog_product", "name");
        $catalogProductEntityVarchar = $this->getTable('catalog_product_entity_varchar');

        $this->getSelect()->join(
            $catalogProductEntityVarchar.' as cpev',
            'main_table.product_id = cpev.entity_id',
            ["product_name" => "value", "mage_store_id" => "store_id"]
        )->where("cpev.store_id = 0 AND cpev.attribute_id = ".$proAttrId);

        $joinTable = $this->getTable('customer_grid_flat');

        $this->getSelect()->join(
            $joinTable.' as cgf',
            'main_table.buyer_id = cgf.entity_id',
            [
                'buyer_name'=>'name'
            ]
        );
        $this->getSelect()->joinLeft(
            $joinTable.' as cgf1',
            'main_table.seller_id = cgf1.entity_id',
            [
                'seller_name'=>'name'
            ]
        );
        $this->addFilterToMap("product_name", "cpev.value");
        $this->addFilterToMap("mage_store_id", "cpev.store_id");
        $this->addFilterToMap("buyer_name", "cgf.name");
        $this->addFilterToMap("seller_name", "cgf1.name");

        parent::_initSelect();
    }
}
