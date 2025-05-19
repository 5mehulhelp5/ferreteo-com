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
use Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Renderer\AddOption;
use Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Renderer\Attribute as AttributeBlock;
use Mageplaza\ProductFinder\Controller\Adminhtml\Rule;

/**
 * Class Attribute
 * @package Mageplaza\ProductFinder\Controller\Adminhtml\Rule
 */
class Attribute extends Rule
{
    /**
     * @return ResponseInterface|Raw|ResultInterface
     */
    public function execute()
    {
        $resultRaw = $this->_resultRawFactory->create();
        $mode      = $this->getRequest()->getParam('mode');

        if ($mode === 'auto') {
            return $resultRaw->setContents(
                $this->createBlock(
                    AttributeBlock::class,
                    'mpproductfinder.rule.attribute'
                )
            );
        }

        return $resultRaw->setContents(
            $this->createBlock(
                AddOption::class,
                'mpproductfinder.rule.addoption'
            )
        );
    }
}
