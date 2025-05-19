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

namespace Mageplaza\ProductFinder\Block;

use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\Data\Helper\PostHelper;
use Mageplaza\ProductFinder\Helper\Data as HelperData;
use Mageplaza\ProductFinder\Model\ResourceModel\Promoted\CollectionFactory as PromotedFactory;

/**
 * Class Promoted
 * @package Mageplaza\ProductFinder\Block
 */
class Promoted extends AbstractProduct
{
    /**
     * @var PromotedFactory
     */
    protected $promotedFactory;

    /**
     * @var ProductCollectionFactory
     */
    protected $productFactory;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var PostHelper
     */
    protected $postHelper;

    /**
     * Promoted constructor.
     *
     * @param PromotedFactory $promotedFactory
     * @param ProductCollectionFactory $productFactory
     * @param HelperData $helperData
     * @param Context $context
     * @param PostHelper $postHelper
     * @param array $data
     */
    public function __construct(
        PromotedFactory $promotedFactory,
        ProductCollectionFactory $productFactory,
        HelperData $helperData,
        Context $context,
        PostHelper $postHelper,
        array $data = []
    ) {
        $this->promotedFactory = $promotedFactory;
        $this->productFactory  = $productFactory;
        $this->helperData      = $helperData;
        $this->postHelper      = $postHelper;
        parent::__construct($context, $data);
    }

    /**
     * @return Collection|null
     */
    public function getPromotedCollection()
    {
        $products = $this->productFactory->create();
        $promoted = $this->promotedFactory->create();
        $sku      = [];
        $param    = $this->getRequest()->getParams();
        if (array_key_exists('rule_id', $param)) {
            $promoted->addFieldToFilter('rule_id', $param['rule_id']);

            foreach ($promoted->getData() as $data) {
                $sku[] = $data['product_sku'];
            }
            if ($sku) {
                $products->addFieldToSelect('*')->addAttributeToFilter('sku', $sku);

                return $products;
            }

            return null;
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function isShowPromoted()
    {
        return $this->helperData->getConfigGeneral('show_promoted');
    }

    /**
     * @param string $url
     * @param array $data
     *
     * @return string
     */
    public function getPostData($url, $data)
    {
        return $this->postHelper->getPostData($url, $data);
    }
}
