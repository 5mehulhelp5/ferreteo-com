<?php
namespace Magecomp\Cityandregionmanager\Block\Adminhtml\System\States\Button;

use Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Generic;

class Tostatesbutton extends Generic
{
    public function getButtonData()
    {
        $url = $this->getUrl('magecomp_cityandregionmanager/states/edit');
        return [
            'label' => __('Add New State'),
            'on_click' => "window.location='{$url}';",
            'sort_order' => 100
        ];
    }
}