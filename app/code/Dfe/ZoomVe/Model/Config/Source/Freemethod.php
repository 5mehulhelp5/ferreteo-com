<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Dfe\ZoomVe\Model\Config\Source;

/**
 * Class Freemethod
 */
class Freemethod extends \Dfe\ZoomVe\Model\Config\Source\Method
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
