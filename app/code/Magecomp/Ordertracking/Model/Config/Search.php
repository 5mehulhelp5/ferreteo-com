<?php
namespace Magecomp\Ordertracking\Model\Config;

/**
 * Class Design
 * Magecomp\Ordertracking\Model\Config
 */
class Search implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => '1', 'label' => __('Search by ZOOM shipping number')],
            ['value' => '2' , 'label' => __('Searched by reference')]

        ];
    }
}

