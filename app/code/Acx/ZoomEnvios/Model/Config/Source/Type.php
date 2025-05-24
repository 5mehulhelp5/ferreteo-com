<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Acx\ZoomEnvios\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Type
 */
class Type implements OptionSourceInterface
{
	/**
	 * {@inheritdoc}
	 */
	function toOptionArray()
	{
		return [
			['value' => 'UPS', 'label' => __('United Parcel Service')],
			['value' => 'UPS_XML', 'label' => __('United Parcel Service XML')]
		];
	}
}
