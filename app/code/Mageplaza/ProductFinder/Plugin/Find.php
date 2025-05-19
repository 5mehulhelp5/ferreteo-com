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

namespace Mageplaza\ProductFinder\Plugin;

use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Exception\LocalizedException;
use Mageplaza\ProductFinder\Helper\Data as HelperData;

/**
 * Class Find
 * @package Mageplaza\ProductFinder\Plugin
 */
class Find
{
    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var Http
     */
    protected $request;

    /**
     * Find constructor.
     *
     * @param HelperData $helperData
     * @param Http $request
     */
    public function __construct(
        HelperData $helperData,
        Http $request
    ) {
        $this->helperData = $helperData;
        $this->request    = $request;
    }

    /**
     * @param Layer $subject
     * @param Collection $result
     *
     * @return mixed
     * @SuppressWarnings(Unused)
     * @throws LocalizedException
     */
    public function afterGetProductCollection(Layer $subject, $result)
    {
        if (!$this->helperData->isEnabled()
            || $this->request->getModuleName() !== 'mpproductfinder') {
            return $result;
        }

        if ($this->isFinding()) {
            $sku = $this->helperData->getSkuCollection();
        } else {
            $sku = $this->helperData->getSkuByRuleId($this->request->getParam('rule_id'));
        }

        $result->distinct(true)->addAttributeToFilter('entity_id', ['in' => $this->helperData->getIdsBySku($sku)]);

        return $result;
    }

    /**
     * @return bool
     */
    public function isFinding()
    {
        $data = $this->request->getParams();
        foreach ($data as $key => $value) {
            if ($value && strpos($key, 'filter') !== false) {
                return true;
            }
        }

        return false;
    }
}
