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

namespace Mageplaza\ProductFinder\Controller;

use Magento\Framework\App\Action\Forward;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use Mageplaza\ProductFinder\Helper\Data as HelperData;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule\CollectionFactory;

/**
 * Class Router
 * @package Mageplaza\ProductFinder\Controller
 */
class Router implements RouterInterface
{
    /**
     * @var ActionFactory
     */
    protected $actionFactory;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var CollectionFactory
     */
    protected $ruleCollection;

    /**
     * Router constructor.
     *
     * @param ActionFactory $actionFactory
     * @param HelperData $helperData
     * @param CollectionFactory $ruleCollection
     */
    public function __construct(
        ActionFactory $actionFactory,
        HelperData $helperData,
        CollectionFactory $ruleCollection
    ) {
        $this->actionFactory  = $actionFactory;
        $this->helperData     = $helperData;
        $this->ruleCollection = $ruleCollection;
    }

    /**
     * @param RequestInterface $request
     *
     * @return ActionInterface|null
     */
    public function match(RequestInterface $request)
    {
        if (!$this->helperData->isEnabled()) {
            return null;
        }

        $identifier = trim($request->getPathInfo(), '/');
        $rule       = $this->ruleCollection->create()->addFieldToFilter('result_url', $identifier);

        if ($rule->getData() && strpos($identifier, $rule->getData()[0]['result_url']) !== false) {
            $request->setModuleName('mpproductfinder');
            $request->setControllerName('finder');
            $request->setActionName('find');
            $request->setParam('rule_id', $rule->getData()[0]['rule_id']);

            return $this->actionFactory->create(Forward::class);
        }

        return null;
    }
}
