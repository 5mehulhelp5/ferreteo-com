<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category  Mageplaza
 * @package   Mageplaza_ProductFinder
 * @copyright Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license   https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\ProductFinder\Block\Navigation;

use Magento\Framework\View\Element\Template\Context;
use Magento\LayeredNavigation\Block\Navigation\FilterRenderer as CoreFilterRenderer;
use Mageplaza\ProductFinder\Helper\Data;

/**
 * Class FilterRenderer
 * @package Mageplaza\ProductFinder\Block\Navigation
 */
class FilterRenderer extends CoreFilterRenderer
{
    /**
     * @var Data
     */
    protected $helperData;

    /**
     * FilterRenderer constructor.
     *
     * @param Context $context
     * @param Data $helperData
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $helperData,
        array $data = []
    ) {
        $this->helperData = $helperData;
        parent::__construct($context, $data);
    }

    /**
     * @return CoreFilterRenderer|FilterRenderer
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->helperData->isLayeredEnable()) {
            return $this->setTemplate('Mageplaza_LayeredNavigation::layer/filter.phtml');
        }

        return $this->setTemplate('Magento_LayeredNavigation::layer/filter.phtml');
    }
}
