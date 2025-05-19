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
use Magento\Framework\Data\Form;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Mageplaza\ProductFinder\Helper\Data as HelperData;
use Mageplaza\ProductFinder\Model\ResourceModel\Filter as ResourceFilter;
use Mageplaza\ProductFinder\Model\ResourceModel\Options as ResourceOptions;

/**
 * Class AddProduct
 * @package Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Renderer
 */
class AddProduct extends Generic
{
    /**
     * @var HelperData
     */
    protected $_helperData;

    /**
     * @var ResourceFilter
     */
    protected $resourceFilter;

    /**
     * @var ResourceOptions
     */
    protected $resourceOptions;

    /**
     * AddProduct constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param HelperData $helperData
     * @param ResourceFilter $resourceFilter
     * @param ResourceOptions $resourceOptions
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        HelperData $helperData,
        ResourceFilter $resourceFilter,
        ResourceOptions $resourceOptions,
        array $data = []
    ) {
        $this->_helperData     = $helperData;
        $this->resourceFilter  = $resourceFilter;
        $this->resourceOptions = $resourceOptions;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return Generic
     * @throws LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var Form $form */
        $form     = $this->_formFactory->create();
        $fieldset = $form->addFieldset('base_fieldset', []);
        $fieldset->addField('product_sku', 'text', [
            'name'  => 'product_sku',
            'label' => __('SKU'),
            'title' => __('SKU')
        ]);

        $ruleId  = $this->_helperData->getRuleId();
        $filters = $this->resourceFilter->getFilterByRuleId($ruleId);

        foreach ($filters as $filter) {
            $options = $this->resourceOptions->toOptionArray($filter['filter_id']);
            if (count($options) > 0) {
                $fieldset->addField($filter['filter_id'], 'select', [
                    'name'   => 'filter_' . $filter['filter_id'],
                    'label'  => $filter['name'],
                    'title'  => $filter['name'],
                    'values' => $options,
                    'class'  => 'mppf-select-filter-option-modal'
                ]);
            }
        }

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
