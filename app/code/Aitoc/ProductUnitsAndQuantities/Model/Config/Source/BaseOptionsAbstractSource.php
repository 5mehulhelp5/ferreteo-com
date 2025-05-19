<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class BaseOptionsAbstractSource
 */
abstract class BaseOptionsAbstractSource implements OptionSourceInterface
{
    /**
     * @return array
     */
    abstract protected function toArray();

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $ret = [];

        foreach ($this->toArray() as $key => $value) {
            $ret[] = $this->keyValueToOptionItem($key, $value);
        }

        return $ret;
    }

    /**
     * @inheritdoc
     */
    public function getAllOptions()
    {
        return $this->toOptionArray();
    }

    /**
     * @param string $key
     * @param string $value
     * @return array
     */
    private function keyValueToOptionItem($key, $value)
    {
        return [
            'value' => $key,
            'label' => $value,
        ];
    }
}
