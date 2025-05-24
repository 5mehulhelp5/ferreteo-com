<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Acx\ZoomEnvios\Model\Config\Source;

/**
 * Class OriginShipment
 */
class OriginShipment extends \Acx\ZoomEnvios\Model\Config\Source\Generic
{
	/**
	 * Carrier code
	 *
	 * @var string
	 */
	protected $_code = 'originShipment';

	/**
	 * {@inheritdoc}
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 */
	function toOptionArray()
	{
		$orShipArr = $this->carrierConfig->getCode($this->_code);
		$returnArr = [];
		foreach ($orShipArr as $key => $val) {
			$returnArr[] = ['value' => $key, 'label' => $key];
		}
		return $returnArr;
	}
}
