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
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\LayoutFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;
use Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Products\Grid;
use Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Renderer\AddProduct as AddProductPopup;
use Mageplaza\ProductFinder\Controller\Adminhtml\Rule;
use Mageplaza\ProductFinder\Helper\Data;
use Mageplaza\ProductFinder\Model\FilterFactory;
use Mageplaza\ProductFinder\Model\ResourceModel\Filter as ResourceFilter;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule as ResourceRule;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule\CollectionFactory;
use Mageplaza\ProductFinder\Model\RuleFactory;
use Psr\Log\LoggerInterface;

/**
 * Class DeleteFilter
 * @package Mageplaza\ProductFinder\Controller\Adminhtml\Rule
 */
class DeleteFilter extends Rule
{
    /**
     * @var FilterFactory
     */
    protected $_filterFactory;

    /**
     * @var ResourceFilter
     */
    protected $_resourceFilter;

    /**
     * DeleteFilter constructor.
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
     * @param FilterFactory $filterFactory
     * @param ResourceFilter $resourceFilter
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
        FilterFactory $filterFactory,
        ResourceFilter $resourceFilter
    ) {
        $this->_filterFactory  = $filterFactory;
        $this->_resourceFilter = $resourceFilter;
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
     * @throws Exception
     */
    public function execute()
    {
        $result   = $this->_resultRawFactory->create();
        $filter   = $this->_filterFactory->create();
        $filterId = $this->getRequest()->getParam('filterId');
        $this->_resourceFilter->load($filter, $filterId)->delete($filter);

        if ($this->getRequest()->getParam('mode') === 'manual') {
            return $result->setContents(
                Data::jsonEncode(
                    [
                        'products' => $this->createBlock(
                            AddProductPopup::class,
                            'mpproductfinder.filter.add.product'
                        ),
                        'grid'     => $this->createBlock(
                            Grid::class,
                            'mpproductfinder.filter.product.grid'
                        )
                    ]
                )
            );
        }

        return null;
    }
}
