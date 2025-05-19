<?php


namespace Magebees\SubcategoryListing\Model\Config\Source;

class SubcategoriesLayout implements \Magento\Framework\Option\ArrayInterface
{

    public function toOptionArray()
    {
        return [['value' => 'Grid', 'label' => __('Grid')],['value' => 'List', 'label' => __('List')]];
    }

    public function toArray()
    {
        return ['Grid' => __('Grid'),'List' => __('List')];
    }
}
