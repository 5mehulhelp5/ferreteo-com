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
 * @category    Mageplaza
 * @package     Mageplaza_ProductFinder
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\ProductFinder\Controller\Adminhtml\Rule;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\ResultInterface;
use Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Promoted\Grid;
use Mageplaza\ProductFinder\Controller\Adminhtml\Rule;

/**
 * Class Promoted
 * @package Mageplaza\ProductFinder\Controller\Adminhtml\Rule
 */
class Promoted extends Rule
{
    /**
     * @return ResponseInterface|Raw|ResultInterface
     */
    public function execute()
    {
        $resultRaw = $this->_resultRawFactory->create();

        return $resultRaw->setContents(
            $this->createBlock(
                Grid::class,
                'mpproductfinder.rule.promoted.products'
            )
        );
    }
}
