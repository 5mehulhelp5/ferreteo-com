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

use Magento\Catalog\Model\Layer\AvailabilityFlagInterface;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Framework\View\Element\Template\Context;
use Magento\LayeredNavigation\Block\Navigation;
use Mageplaza\ProductFinder\Helper\Data;
use Mageplaza\ProductFinder\Model\Layer\FilterList;

/**
 * Class FinderNavigation
 * @package Mageplaza\ProductFinder\Block\Navigation
 */
class FinderNavigation extends Navigation
{
    /**
     * @var Data
     */
    protected $helperData;

    /**
     * FinderNavigation constructor.
     *
     * @param Context $context
     * @param Resolver $layerResolver
     * @param FilterList $filterList
     * @param AvailabilityFlagInterface $visibilityFlag
     * @param Data $helperData
     * @param array $data
     */
    public function __construct(
        Context $context,
        Resolver $layerResolver,
        FilterList $filterList,
        AvailabilityFlagInterface $visibilityFlag,
        Data $helperData,
        array $data = []
    ) {
        $this->helperData = $helperData;
        parent::__construct($context, $layerResolver, $filterList, $visibilityFlag, $data);
    }

    /**
     * @return Navigation|FinderNavigation
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->helperData->isLayeredEnable()) {
            return $this->setTemplate('Mageplaza_LayeredNavigation::layer/view.phtml');
        }

        return $this->setTemplate('Magento_LayeredNavigation::layer/view.phtml');
    }
}
