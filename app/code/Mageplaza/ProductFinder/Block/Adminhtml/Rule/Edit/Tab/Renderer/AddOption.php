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

namespace Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Renderer;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Eav\Block\Adminhtml\Attribute\Edit\Options\Options as EavOptions;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Store\Model\Store;
use Mageplaza\ProductFinder\Helper\Data as HelperData;
use Mageplaza\ProductFinder\Helper\Image as HelperImage;
use Mageplaza\ProductFinder\Model\ResourceModel\Options\CollectionFactory as OptionsCollection;

/**
 * Class AddOption
 * @package Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Renderer
 */
class AddOption extends Generic
{
    /**
     * @var string
     */
    protected $_template = 'Mageplaza_ProductFinder::form/renderer/addoption.phtml';

    /**
     * @var EavOptions
     */
    protected $eavOptions;

    /**
     * @var OptionsCollection
     */
    protected $optionsCollection;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var HelperImage
     */
    protected $helperImage;

    /**
     * AddOption constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param EavOptions $eavOptions
     * @param OptionsCollection $optionsCollection
     * @param HelperData $helperData
     * @param HelperImage $helperImage
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        EavOptions $eavOptions,
        OptionsCollection $optionsCollection,
        HelperData $helperData,
        HelperImage $helperImage,
        array $data = []
    ) {
        $this->eavOptions        = $eavOptions;
        $this->optionsCollection = $optionsCollection;
        $this->helperData        = $helperData;
        $this->helperImage       = $helperImage;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return mixed
     */
    public function getFilterId()
    {
        return $this->getRequest()->getParam('filterId');
    }

    /**
     * @return array
     */
    public function getOptionsByFilter()
    {
        $filterId   = $this->getFilterId();
        $collection = $this->optionsCollection->create()->addFieldToFilter('filter_id', $filterId);

        return $collection->getData();
    }

    /**
     * @return array
     */
    public function getStoresSortedBySortOrder()
    {
        return $this->eavOptions->getStoresSortedBySortOrder();
    }

    /**
     * @return int
     */
    public function getDefaultStoreId()
    {
        return Store::DEFAULT_STORE_ID;
    }

    /**
     * @return string
     */
    public function getAddOptionsUrl()
    {
        return $this->getUrl('*/*/addOptions', ['form_key' => $this->getFormKey()]);
    }

    /**
     * @return string
     */
    public function getDeleteOptionUrl()
    {
        return $this->getUrl('*/*/deleteOption', ['form_key' => $this->getFormKey()]);
    }

    /**
     * @param string $value
     *
     * @return mixed
     */
    public function decodeStoreValue($value)
    {
        return HelperData::jsonDecode($value);
    }

    /**
     * @param string $image
     *
     * @return string
     */
    public function getImageUrl($image)
    {
        return $this->helperImage->getImageUrl($image);
    }
}
