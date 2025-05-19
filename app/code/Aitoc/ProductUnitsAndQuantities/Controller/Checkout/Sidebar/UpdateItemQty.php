<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Controller\Checkout\Sidebar;

use Magento\Checkout\Controller\Sidebar\UpdateItemQty as BaseUpdateItemQty;
use Magento\Checkout\Model\Sidebar;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Json\Helper\Data;
use Psr\Log\LoggerInterface;

/**
 * Class UpdateItemQty
 *
 * Bugfix for decimal qty was set from minicart. Fixed in v2.3.
 * @see https://github.com/magento/magento2/pull/13517
 */
class UpdateItemQty extends BaseUpdateItemQty
{
    /**
     * @var ProductMetadataInterface
     */
    private $productMetadata;

    /**
     * UpdateItemQty constructor.
     * @param Context $context
     * @param Sidebar $sidebar
     * @param LoggerInterface $logger
     * @param Data $jsonHelper
     * @param ProductMetadataInterface $productMetadata
     */
    public function __construct(
        Context $context,
        Sidebar $sidebar,
        LoggerInterface $logger,
        Data $jsonHelper,
        ProductMetadataInterface $productMetadata
    ) {
        parent::__construct($context, $sidebar, $logger, $jsonHelper);
        $this->productMetadata = $productMetadata;
    }

    /**
     * @inheritdoc
     */
    private function isBuggyCurrentCoreVersion()
    {
        $shouldBeFixedInVersion = '2.3.0';
        $magentoVersion = $this->productMetadata->getVersion();

        if (version_compare($magentoVersion, $shouldBeFixedInVersion, '<')) {
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        if (!$this->isBuggyCurrentCoreVersion()) {
            return parent::execute();
        }

        $itemId = (int)$this->getRequest()->getParam('item_id');
        $itemQty = $this->getRequest()->getParam('item_qty') * 1;

        try {
            $this->sidebar->checkQuoteItem($itemId);
            $this->sidebar->updateQuoteItem($itemId, $itemQty);
            return $this->jsonResponse();
        } catch (LocalizedException $e) {
            return $this->jsonResponse($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->critical($e);
            return $this->jsonResponse($e->getMessage());
        }
    }
}
