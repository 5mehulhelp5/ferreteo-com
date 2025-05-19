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
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\Registry;
use Magento\Framework\View\LayoutFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;
use Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Renderer\AddProduct as AddProductBlock;
use Mageplaza\ProductFinder\Controller\Adminhtml\Rule;
use Mageplaza\ProductFinder\Helper\Data;
use Mageplaza\ProductFinder\Helper\Data as HelperData;
use Mageplaza\ProductFinder\Helper\Image as HelperImage;
use Mageplaza\ProductFinder\Model\OptionsFactory;
use Mageplaza\ProductFinder\Model\ResourceModel\Options as ResourceOptions;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule as ResourceRule;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule\CollectionFactory;
use Mageplaza\ProductFinder\Model\RuleFactory;
use Psr\Log\LoggerInterface;

/**
 * Class AddOptions
 * @package Mageplaza\ProductFinder\Controller\Adminhtml\Rule
 */
class AddOptions extends Rule
{
    /**
     * @var HelperImage
     */
    protected $helperImage;

    /**
     * @var ResourceOptions
     */
    protected $resourceOptions;

    /**
     * @var OptionsFactory
     */
    protected $optionsFactory;

    /**
     * @var JsonHelper
     */
    protected $jsonHelper;

    /**
     * AddOptions constructor.
     *
     * @param Context $context
     * @param ForwardFactory $resultForwardFactory
     * @param PageFactory $resultPageFactory
     * @param Registry $coreRegistry
     * @param RuleFactory $ruleFactory
     * @param ResourceRule $resourceRule
     * @param HelperData $helperData
     * @param LoggerInterface $logger
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param RawFactory $resultRawFactory
     * @param LayoutFactory $layoutFactory
     * @param JsonFactory $resultJsonFactory
     * @param HelperImage $helperImage
     * @param ResourceOptions $resourceOptions
     * @param OptionsFactory $optionsFactory
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
        HelperImage $helperImage,
        ResourceOptions $resourceOptions,
        OptionsFactory $optionsFactory,
        JsonHelper $jsonHelper
    ) {
        $this->helperImage     = $helperImage;
        $this->resourceOptions = $resourceOptions;
        $this->optionsFactory  = $optionsFactory;
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
     * @throws AlreadyExistsException
     */
    public function execute()
    {
        $result = ['success' => true];
        $data   = $this->getRequest()->getParams();

        if (array_key_exists('option', $data)) {
            foreach ($data['option'] as $key => $value) {
                $this->helperImage->_uploadImage($value, $result, 'image-' . $key);
                if (array_key_exists('option_id', $value)) {
                    $filterData = [
                        'option_id' => $value['option_id'],
                        'filter_id' => $data['filter_id'],
                        'name'      => $value['name'],
                        'image'     => $value['image'],
                        'value'     => HelperData::jsonEncode($value['store'])
                    ];
                } else {
                    $filterData = [
                        'filter_id' => $data['filter_id'],
                        'name'      => $value['name'],
                        'image'     => $value['image'],
                        'value'     => HelperData::jsonEncode($value['store'])
                    ];
                }

                $options = $this->optionsFactory->create();
                $options->setData($filterData);
                $this->resourceOptions->save($options);
            }
        }

        $result['add_product_popup'] = $this->createBlock(
            AddProductBlock::class,
            'mpproductfinder.filter.add.product'
        );

        $this->getResponse()->representJson($this->jsonHelper->jsonEncode($result));
    }
}
