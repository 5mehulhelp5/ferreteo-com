<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-cataloglabel
 * @version   1.1.21
 * @copyright Copyright (C) 2021 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\CatalogLabel\Model\ResourceModel\Label;

/**
 *
 * @SuppressWarnings(PHPMD)
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'label_id';//@codingStandardsIgnoreLine

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Data\Collection\EntityFactoryInterface
     */
    protected $entityFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\Data\Collection\Db\FetchStrategyInterface
     */
    protected $fetchStrategy;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $eventManager;

    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $connection;

    /**
     * @var \Magento\Framework\Model\ResourceModel\Db\AbstractDb
     */
    protected $resource;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface                   $storeManager
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface    $entityFactory
     * @param \Psr\Log\LoggerInterface                                     $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface                    $eventManager
     * @param \Magento\Framework\DB\Adapter\AdapterInterface               $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb         $resource
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        $this->storeManager = $storeManager;
        $this->entityFactory = $entityFactory;
        $this->logger = $logger;
        $this->fetchStrategy = $fetchStrategy;
        $this->eventManager = $eventManager;
        $this->connection = $connection;
        $this->resource = $resource;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Mirasvit\CatalogLabel\Model\Label', 'Mirasvit\CatalogLabel\Model\ResourceModel\Label');
    }

    /**
     * @return $this
     */
    public function addActiveFilter()
    {
        $date = new \Zend_Date();

        $activeFrom = [];
        $activeFrom[] = ['date' => true, 'to' => date($date->toString('YYYY-MM-dd H:mm:ss'))];
        $activeFrom[] = ['date' => true, 'null' => true];

        $activeTo = [];
        $activeTo[] = ['date' => true, 'from' => date($date->toString('YYYY-MM-dd H:mm:ss'))];
        $activeTo[] = ['date' => true, 'null' => true];

        $this->addFieldToFilter('is_active', 1);
        $this->addFieldToFilter('active_from', $activeFrom);
        $this->addFieldToFilter('active_to', $activeTo);

        return $this;
    }

    /**
     * @param int $groupId
     * @return $this
     */
    public function addCustomerGroupFilter($groupId)
    {
        $this->getSelect()
            ->joinInner(
                ['group_table' => $this->getTable('mst_cataloglabel_label_customer_group')],
                'main_table.label_id = group_table.label_id AND group_table.customer_group_id = '.$groupId, []
            )
        ;

        return $this;
    }

    /**
     * @param array|\Magento\Store\Model\Store $store
     * @return $this
     */
    public function addStoreFilter($store)
    {
        if (!$this->storeManager->isSingleStoreMode()) {
            if ($store instanceof \Magento\Store\Model\Store) {
                $store = [$store->getId()];
            }

            $this->getSelect()
                ->join(
                    ['store_table' => $this->getTable('mst_cataloglabel_label_store')],
                    'main_table.label_id = store_table.label_id', []
                )
                ->where('store_table.store_id in (?)', [0, $store]);
        }

        return $this;
    }
}
