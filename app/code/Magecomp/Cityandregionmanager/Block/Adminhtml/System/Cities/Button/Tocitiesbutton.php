<?php

namespace Magecomp\Cityandregionmanager\Block\Adminhtml\System\Cities\Button;

use Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Generic;

class Tocitiesbutton extends Generic
{
    public function getButtonData()
    {
        $url = $this->getUrl('magecomp_cityandregionmanager/cities/edit');
        return [
            'label' => __('Add New City'),
            'on_click' => "window.location='{$url}';",
            'sort_order' => 100
        ];
    }
}