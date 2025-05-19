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

namespace Mageplaza\ProductFinder\Model\ResourceModel\Layer\Filter;

use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\ResourceModel\Layer\Filter\Price as CorePrice;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Http\Context;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\DB\Select;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Indexer\DimensionFactory;
use Magento\Framework\Model\ResourceModel\Db\Context as DbContext;
use Magento\Framework\Search\Request\IndexScopeResolverInterface;
use Magento\Store\Model\StoreManagerInterface;
use Zend_Db_Select_Exception;

/**
 * Class Price
 * @package Mageplaza\ProductFinder\Model\ResourceModel\Layer\Filter
 */
class Price extends CorePrice
{
    /**
     * @var Layer
     */
    protected $layer;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Http
     */
    protected $request;

    /**
     * @var ProductMetadataInterface
     */
    protected $productMeta;

    /**
     * Price constructor.
     *
     * @param Layer $layer
     * @param Http $request
     * @param ProductMetadataInterface $productMeta
     * @param DbContext $context
     * @param ManagerInterface $eventManager
     * @param Resolver $layerResolver
     * @param Session $session
     * @param StoreManagerInterface $storeManager
     * @param null $connectionName
     * @param IndexScopeResolverInterface|null $priceTableResolver
     * @param Context|null $httpContext
     * @param DimensionFactory|null $dimensionFactory
     */
    public function __construct(
        Layer $layer,
        Http $request,
        ProductMetadataInterface $productMeta,
        DbContext $context,
        ManagerInterface $eventManager,
        Resolver $layerResolver,
        Session $session,
        StoreManagerInterface $storeManager,
        $connectionName = null,
        IndexScopeResolverInterface $priceTableResolver = null,
        Context $httpContext = null,
        DimensionFactory $dimensionFactory = null
    ) {
        $this->layer        = $layer;
        $this->session      = $session;
        $this->storeManager = $storeManager;
        $this->request      = $request;
        $this->productMeta  = $productMeta;
        parent::__construct(
            $context,
            $eventManager,
            $layerResolver,
            $session,
            $storeManager,
            $connectionName,
            $priceTableResolver,
            $httpContext,
            $dimensionFactory
        );
    }

    /**
     * @return Select
     * @throws NoSuchEntityException
     * @throws Zend_Db_Select_Exception
     */
    protected function _getSelect()
    {
        if ($this->request->getFullActionName() !== 'mpproductfinder_finder_find'
            || $this->productMeta->getEdition() === 'Community') {
            return parent::_getSelect();
        }
        $collection = $this->layer->getProductCollection();
        $collection->addPriceData(
            $this->session->getCustomerGroupId(),
            $this->storeManager->getStore()->getWebsiteId()
        );

        if ($collection->getCatalogPreparedSelect() !== null) {
            $select = clone $collection->getCatalogPreparedSelect();
        } else {
            $select = clone $collection->getSelect();
        }

        // reset columns, order and limitation conditions
        $select->reset(Select::COLUMNS);
        $select->reset(Select::ORDER);
        $select->reset(Select::LIMIT_COUNT);
        $select->reset(Select::LIMIT_OFFSET);

        // remove join with main table
        $fromPart = $select->getPart(Select::FROM);
        if (!isset($fromPart[Collection::INDEX_TABLE_ALIAS], $fromPart[Collection::MAIN_TABLE_ALIAS])
        ) {
            return $select;
        }

        // processing WHERE part
        $wherePart = $select->getPart(Select::WHERE);
        $select->setPart(Select::WHERE, $wherePart);
        $select->where($this->_getPriceExpression($select) . ' IS NOT NULL');

        return $select;
    }

    /**
     * @param Select $select
     * @param bool $replaceAlias
     *
     * @return string
     */
    protected function _getPriceExpression($select, $replaceAlias = true)
    {
        if ($this->request->getFullActionName() !== 'mpproductfinder_finder_find') {
            return parent::_getPriceExpression($select, $replaceAlias);
        }
        $priceExpression           = $this->layer->getProductCollection()->getPriceExpression($select);
        $additionalPriceExpression = $this->layer->getProductCollection()->getAdditionalPriceExpression($select);
        $result                    = empty($additionalPriceExpression)
            ? $priceExpression
            : "({$priceExpression} {$additionalPriceExpression})";

        return $result;
    }
}
