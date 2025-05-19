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

use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection as CollectionEav;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory as EavCollection;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Mageplaza\ProductFinder\Helper\Data as HelperData;
use Mageplaza\ProductFinder\Helper\Image as HelperImage;
use Mageplaza\ProductFinder\Model\Config\Source\Template as TemplateOption;
use Mageplaza\ProductFinder\Model\Filter;
use Mageplaza\ProductFinder\Model\ResourceModel\Filter as ResourceFilter;
use Mageplaza\ProductFinder\Model\ResourceModel\Filter\CollectionFactory as FilterCollection;
use Mageplaza\ProductFinder\Model\ResourceModel\Options\Collection as CollectionOptions;
use Mageplaza\ProductFinder\Model\ResourceModel\Options\CollectionFactory as OptionsCollection;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule\Collection;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule\CollectionFactory as RuleCollection;
use Mageplaza\ProductFinder\Model\Rule;

/**
 * Class Finder
 * @package Mageplaza\ProductFinder\Block
 */
class Finder extends Template
{
    /**
     * @var RuleCollection
     */
    protected $ruleCollection;

    /**
     * @var FilterCollection
     */
    protected $filterCollection;

    /**
     * @var ResourceFilter
     */
    protected $resourceFilter;

    /**
     * @var EavCollection
     */
    protected $eavCollection;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var OptionsCollection
     */
    protected $optionsCollection;

    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var FormKey
     */
    protected $formKey;

    /**
     * @var HelperImage
     */
    protected $helperImage;

    /**
     * Finder constructor.
     *
     * @param RuleCollection $ruleCollection
     * @param FilterCollection $filterCollection
     * @param ResourceFilter $resourceFilter
     * @param EavCollection $eavCollection
     * @param HelperData $helperData
     * @param OptionsCollection $optionsCollection
     * @param Registry $coreRegistry
     * @param Context $context
     * @param FormKey $formKey
     * @param HelperImage $helperImage
     * @param array $data
     */
    public function __construct(
        RuleCollection $ruleCollection,
        FilterCollection $filterCollection,
        ResourceFilter $resourceFilter,
        EavCollection $eavCollection,
        HelperData $helperData,
        OptionsCollection $optionsCollection,
        Registry $coreRegistry,
        Context $context,
        FormKey $formKey,
        HelperImage $helperImage,
        array $data = []
    ) {
        $this->ruleCollection    = $ruleCollection;
        $this->filterCollection  = $filterCollection;
        $this->resourceFilter    = $resourceFilter;
        $this->eavCollection     = $eavCollection;
        $this->helperData        = $helperData;
        $this->optionsCollection = $optionsCollection;
        $this->coreRegistry      = $coreRegistry;
        $this->formKey           = $formKey;
        $this->helperImage       = $helperImage;
        parent::__construct($context, $data);
    }

    /**
     * @return Collection
     */
    public function getRuleCollection()
    {
        $collection = $this->ruleCollection->create();
        $collection->addFieldToFilter('position', $this->getPosition())
            ->addFieldToFilter('status', true)
            ->setOrder('sort_order', 'asc');
        $fullActionName = $this->getRequest()->getFullActionName();

        if ($fullActionName === 'catalog_category_view') {
            $entityId = $this->coreRegistry->registry('current_category')->getId();
            $collection->addFieldToFilter('categories_ids', ['finset' => $entityId]);
        }

        if ($fullActionName === 'mpproductfinder_finder_find') {
            $collection->addFieldToFilter('rule_id', $this->getRequest()->getParam('rule_id'));
        }

        return $collection;
    }

    /**
     * @param Rule $rule
     *
     * @return float|string
     * @throws LocalizedException
     */
    public function getFilterWidth($rule)
    {
        return $rule->getTemplate() === TemplateOption::HORIZONTAL && $rule->getPosition() !== 'sidebar'
            ? floor(100 / count($this->resourceFilter->getFilterByRuleId($rule->getId()))) - 1 : '';
    }

    /**
     * @param Rule $rule
     *
     * @return ResourceFilter\Collection
     */
    public function getFilterCollection($rule)
    {
        $collection = $this->filterCollection->create();
        $collection->addFieldToFilter('rule_id', $rule->getId());

        return $collection;
    }

    /**
     * @param Filter $filter
     * @param string $order
     *
     * @return CollectionOptions
     */
    public function getOptionsByFilter($filter, $order)
    {
        $collection = $this->optionsCollection->create();
        $collection->addFieldToFilter('filter_id', $filter->getId())->setOrder('option_id', $order);

        return $collection;
    }

    /**
     * @param string $id
     * @param string $order
     *
     * @return CollectionEav
     */
    public function getFilterOptionsByAttrId($id, $order)
    {
        $attribute = $this->eavCollection->create()->setPositionOrder($order)
            ->setAttributeFilter($id)
            ->setStoreFilter()
            ->load();

        return $attribute;
    }

    /**
     * @param string $code
     *
     * @return mixed
     */
    public function getSystemConfig($code)
    {
        return $this->helperData->getConfigGeneral($code);
    }

    /**
     * @param string $resultUrl
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getActionUrl($resultUrl)
    {
        $securedFlag = $this->_storeManager->getStore()->isCurrentlySecure();

        return $this->getUrl($resultUrl, ['_secure' => $securedFlag]);
    }

    /**
     * @return array
     */
    public function getParamsSubmit()
    {
        $params = $this->getRequest()->getParams();
        foreach ($params as $key => $param) {
            if (strpos($key, 'filter') !== false) {
                unset($params[$key]);
            }
        }
        unset($params['rule_id'], $params['id']);

        return $params;
    }

    /**
     * @return string
     */
    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }

    /**
     * @return int
     * @throws NoSuchEntityException
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getOptionNameByStore($value)
    {
        $storeId = (int) $this->getStoreId();
        $names   = HelperData::jsonDecode($value);
        foreach ($names as $key => $name) {
            if ($key === $storeId) {
                return $name === '' ? $names[0] : $name;
            }
        }

        return '';
    }

    /**
     * @param string $image
     *
     * @return string
     */
    public function getImageUrl($image)
    {
        if ($image) {
            return $this->helperImage->getImageUrl($image);
        }

        return $this->getDefaultImage();
    }

    /**
     * @return mixed
     */
    public function getFullActionName()
    {
        return $this->_request->getFullActionName();
    }

    /**
     * @return string
     */
    public function getFinderParams()
    {
        $data               = $this->getRequest()->getParams();
        $filterData['rule'] = $data['rule_id'];
        $select             = [];
        $dropdown           = [];
        unset($data['rule_id']);

        foreach ($data as $key => $value) {
            if ($value && strpos($key, 'filter') !== false) {
                $filterId      = explode('-', $key)[1];
                $filterDisplay = $this->filterCollection->create()
                    ->addFieldToFilter('filter_id', $filterId)
                    ->getData()[0]['display'];

                if ($filterDisplay === 'dropdown') {
                    $select += [$key => $value];
                } else {
                    $dropdown += [$key => $value];
                }
            }
        }

        $filterData['filter'] = compact('select', 'dropdown');

        return HelperData::jsonEncode($filterData);
    }

    /**
     * @return bool
     */
    public function isEnable()
    {
        return $this->helperData->isEnabled();
    }

    /**
     * @return string
     */
    public function getDefaultImage()
    {
        return $this->getViewFileUrl('Magento_Catalog::images/product/placeholder/thumbnail.jpg');
    }
}
