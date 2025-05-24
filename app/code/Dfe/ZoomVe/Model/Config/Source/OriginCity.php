<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Dfe\ZoomVe\Model\Config\Source;

/**
 * Class OriginShipment
 */
class OriginCity extends \Dfe\ZoomVe\Model\Config\Source\Generic
{
	/**
	 * Carrier code
	 *
	 * @var string
	 */
	protected $_code = 'origin_city';

	/**
	 * {@inheritdoc}
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 */
	function toOptionArray()
	{
		$orCityArr = $this->carrierConfig->getCode($this->_code);
		$returnArr = [];
		foreach ($orCityArr as $key => $val) {
			$returnArr[] = ['value' => $key, 'label' => $key];
		}
		return $returnArr;
	}
}
