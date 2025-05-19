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

use Magento\Catalog\Helper\Category as CategoryCollection;
use Magento\Catalog\Model\Category as CategoryModel;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryFactory;
use Magento\Framework\Data\Form\Element\CollectionFactory;
use Magento\Framework\Data\Form\Element\Factory;
use Magento\Framework\Data\Form\Element\Multiselect;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Category
 * @package Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Renderer
 */
class Category extends Multiselect
{
    /**
     * @var CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * Category constructor.
     *
     * @param Factory $factoryElement
     * @param CollectionFactory $factoryCollection
     * @param Escaper $escaper
     * @param CategoryFactory $categoryFactory
     * @param array $data
     */
    public function __construct(
        Factory $factoryElement,
        CollectionFactory $factoryCollection,
        Escaper $escaper,
        CategoryFactory $categoryFactory,
        array $data = []
    ) {
        $this->_categoryFactory = $categoryFactory;
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    public function getElementHtml()
    {
        return $this->getData('category_html') . $this->getAfterElementHtml();
    }

    /**
     * @return mixed
     */
    public function getCategoriesTree()
    {
        /* @var $collection CategoryCollection */
        $collection = $this->_categoryFactory->create()->addAttributeToSelect('*');

        $categoryById = [
            CategoryModel::TREE_ROOT_ID => [
                'value'    => CategoryModel::TREE_ROOT_ID,
                'optgroup' => null,
            ],
        ];

        /** @var array $collection */
        foreach ($collection as $category) {
            foreach ([$category->getId(), $category->getParentId()] as $categoryId) {
                if (!isset($categoryById[$categoryId])) {
                    $categoryById[$categoryId] = ['value' => $categoryId];
                }
            }

            $categoryById[$category->getId()]['is_active']        = 1;
            $categoryById[$category->getId()]['label']            = $category->getName();
            $categoryById[$category->getParentId()]['optgroup'][] = &$categoryById[$category->getId()];
        }

        return $categoryById[CategoryModel::TREE_ROOT_ID]['optgroup'];
    }

    /**
     * @return array
     */
    public function getValues()
    {
        $values = $this->getValue();
        if (!is_array($values)) {
            $values = explode(',', $values);
        }

        if (count($values) < 1) {
            return [];
        }

        /* @var $collection CategoryCollection */
        $collection = $this->_categoryFactory->create()
            ->addIdFilter($values);

        $options = [];
        /** @var array $collection */
        foreach ($collection as $category) {
            $options[] = $category->getId();
        }

        return $options;
    }

    /**
     * @return mixed|string
     * @throws LocalizedException
     */
    public function getAfterElementHtml()
    {
        $html = '<script type="text/x-magento-init">
            {
                "*": {
                    "Magento_Ui/js/core/app": {
                        "components": {
                            "category": {
                                "component": "uiComponent",
                                "children": {
                                    "rule_select_category": {
                                        "component": "Magento_Catalog/js/components/new-category",
                                        "config": {
                                            "filterOptions": true,
                                            "disableLabel": true,
                                            "chipsEnabled": true,
                                            "levelsVisibility": "1",
                                            "elementTmpl": "ui/grid/filters/elements/ui-select",
                                            "options": ' . json_encode($this->getCategoriesTree()) . ',
                                            "value": ' . json_encode($this->getValues()) . ',
                                            "listens": {
                                                "index=create_category:responseData": "setParsed",
                                                "newOption": "toggleOptionSelected"
                                            },
                                            "config": {
                                                "dataScope": "rule_select_category",
                                                "sortOrder": 10
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        </script>';

        return $html;
    }
}
