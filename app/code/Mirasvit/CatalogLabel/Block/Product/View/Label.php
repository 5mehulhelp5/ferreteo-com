<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-cataloglabel
 * @version   1.1.21
 * @copyright Copyright (C) 2021 Mirasvit (https://mirasvit.com/)
 */


namespace Mirasvit\CatalogLabel\Block\Product\View;

class Label extends \Magento\Catalog\Block\Product\View\AbstractView
{
    /**
     * @var \Mirasvit\CatalogLabel\Helper\Data
     */
    private $dataHelper;
    /**
     * @var \Mirasvit\CatalogLabel\Model\Placeholder
     */
    private $placeholder;

    /**
     * @param \Magento\Catalog\Block\Product\Context   $context
     * @param \Magento\Framework\Stdlib\ArrayUtils     $arrayUtils
     * @param \Mirasvit\CatalogLabel\Model\Placeholder $placeholder
     * @param \Mirasvit\CatalogLabel\Helper\Data       $dataHelper
     * @param array                                    $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Stdlib\ArrayUtils $arrayUtils,
        \Mirasvit\CatalogLabel\Model\Placeholder $placeholder,
        \Mirasvit\CatalogLabel\Helper\Data $dataHelper,
        array $data = []
    ) {
        parent::__construct($context, $arrayUtils, $data);

        $this->placeholder = $placeholder;
        $this->dataHelper = $dataHelper;
    }

    /**
     * Processing block html after rendering
     *
     * @param string $html
     * @param mixed $product
     * @param string $code
     * @return  string
     */
    protected function _afterToHtml($html, $product = false, $code = 'view')
    {
        $veiwPlaceholders   = $this->placeholder->getCollection()
            ->addFieldTofilter('is_active', 1)
            ->addFieldToFilter('is_auto_for_view', 1)
            ->addFieldToFilter('image_type', ['like' => '%view%']);

        if (!$product) {
            $product = $this->getProduct();
        }

        foreach ($veiwPlaceholders as $p) {
            $ourhtml = $this->dataHelper->getProductHtml($p->getCode(), $product, $code, 'badge');
            if (!$ourhtml) {
                continue;
            }
            $html .= $ourhtml;
        }

        return $html;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     */
    public function getAdditionalHtmlManually($product)
    {
        return $this->_afterToHtml('', $product, 'list');
    }
}
