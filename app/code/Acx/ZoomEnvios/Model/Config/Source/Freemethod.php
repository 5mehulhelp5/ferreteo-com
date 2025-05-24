<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Acx\ZoomEnvios\Model\Config\Source;

/**
 * Class Freemethod
 */
class Freemethod extends \Acx\ZoomEnvios\Model\Config\Source\Method
{
    /**
     * {@inheritdoc}
     */
    function toOptionArray()
    {
        $arr = parent::toOptionArray();
        array_unshift($arr, ['value' => '', 'label' => __('None')]);
        return $arr;
    }
}
