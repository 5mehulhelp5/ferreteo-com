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

namespace Mageplaza\ProductFinder\Controller\Finder;

use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Mageplaza\ProductFinder\Helper\Data as HelperData;
use Mageplaza\ProductFinder\Model\Config\Source\System\ResetPage;
use Mageplaza\ProductFinder\Model\Rule;

/**
 * Class Find
 * @package Mageplaza\ProductFinder\Controller\Finder
 */
class Find extends Action
{
    /**
     * @var PageFactory
     */
    protected $_pageFactory;

    /**
     * @var ProductCollectionFactory
     */
    protected $productCollection;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var Rule
     */
    protected $ruleFactory;

    /**
     * @var JsonHelper
     */
    protected $jsonHelper;

    /**
     * Find constructor.
     *
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param ProductCollectionFactory $productCollection
     * @param HelperData $helperData
     * @param ProductRepository $productRepository
     * @param Rule $ruleFactory
     * @param JsonHelper $jsonHelper
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        ProductCollectionFactory $productCollection,
        HelperData $helperData,
        ProductRepository $productRepository,
        Rule $ruleFactory,
        JsonHelper $jsonHelper
    ) {
        $this->_pageFactory      = $pageFactory;
        $this->productCollection = $productCollection;
        $this->helperData        = $helperData;
        $this->productRepository = $productRepository;
        $this->ruleFactory       = $ruleFactory;
        $this->jsonHelper        = $jsonHelper;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface|Page
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        $resultPage = $this->_pageFactory->create();
        $ruleId     = $this->getRequest()->getParam('rule_id');
        $rule       = $this->ruleFactory->load($ruleId);
        $resultPage->getConfig()->getTitle()->set($rule->getPageTitle());

        if ($this->getRequest()->getParam('reset')) {
            $resetTo = $this->helperData->getConfigGeneral('reset_page');
            if ($resetTo === ResetPage::HOME_PAGE) {
                return $this->resultRedirectFactory->create()->setUrl($this->_url->getBaseUrl());
            }
        }

        $skuCollection = $this->helperData->getSkuCollection();
        $collection    = $this->productCollection->create();
        $collection->addAttributeToSelect('*')->setFlag('has_stock_status_filter', true);
        $collection->joinField(
            'qty',
            'cataloginventory_stock_item',
            'qty',
            'product_id=entity_id',
            '{{table}}.stock_id=1',
            'left'
        )->joinTable('cataloginventory_stock_item', 'product_id=entity_id', ['stock_status' => 'is_in_stock'])
            ->addAttributeToSelect('stock_status')
            ->addAttributeToSort('entity_id', 'DESC')
            ->addAttributeToFilter('status', Status::STATUS_ENABLED)
            ->addAttributeToFilter('sku', ['in' => $skuCollection])
            ->load();

        if ($this->helperData->getConfigGeneral('redirect_product') && count($collection->getData()) === 1) {
            $product = $this->productRepository->get($collection->getFirstItem()->getSku());
            if (isset($product)) {
                $url = $product->getProductUrl();

                return $this->resultRedirectFactory->create()->setUrl($url);
            }
        }

        if ($this->helperData->isLayeredEnable()
            && $this->getRequest()->isAjax()
            && $this->getRequest()->getFullActionName() === 'mpproductfinder_finder_find') {
            switch ($rule->getPosition()) {
                case 'top':
                    $block = 'mpproductfinder.top.container';
                    break;
                case 'sidebar':
                    $block = 'mpproductfinder.sidebar.main';
                    break;
                default:
                    $block = 'mpproductfinder.bottom.container';
                    break;
            }

            $navigation = $resultPage->getLayout()->getBlock('catalog.leftnav');
            $products   = $resultPage->getLayout()->getBlock('category.products');

            $result = [];
            if ($this->helperData->isEnabled()) {
                $finder           = $resultPage->getLayout()->getBlock($block);
                $result['finder'] = $finder->toHtml();
            }
            $result += ['products' => $products->toHtml(), 'navigation' => $navigation->toHtml()];

            return $this->getResponse()->representJson($this->jsonHelper->jsonEncode($result));
        }

        return $resultPage;
    }
}
