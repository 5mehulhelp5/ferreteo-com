<?php
/**
 * @category   Webkul
 * @package    Webkul_MpSellerBadge
 * @author     Webkul Software Private Limited
 * @copyright  Copyright (c)  Webkul Software Private Limited (https://webkul.com)
 * @license    https://store.webkul.com/license.html
 */
namespace Webkul\MpSellerBadge\Model\Badge\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status
 * This contains the available statuses for a badge.
 */
class Status implements OptionSourceInterface
{
    /**
     * @var \Webkul\MpSellerBadge\Model\Badge
     */
    protected $mpbadge;

    /**
     * Constructor
     *
     * @param \Magento\Cms\Model\Page $cmsPage
     */
    public function __construct(\Webkul\MpSellerBadge\Model\Badge $mpbadge)
    {
        $this->mpbadge = $mpbadge;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->mpbadge->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
