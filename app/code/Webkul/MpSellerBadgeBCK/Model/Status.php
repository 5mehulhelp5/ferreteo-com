<?php
/**
 * @category   Webkul
 * @package    Webkul_MpSellerBadge
 * @author     Webkul Software Private Limited
 * @copyright  Copyright (c)  Webkul Software Private Limited (https://webkul.com)
 * @license    https://store.webkul.com/license.html
 */
namespace Webkul\MpSellerBadge\Model;

use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{
    const ENABLE = 1;
    const DISABLE = 0;
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
                        [
                            'label' => 'Disable',
                            'value' => self::DISABLE
                        ],
                        [
                            'label' => 'Enable',
                            'value' => self::ENABLE
                        ]
                    ];
        return $options;
    }
}
