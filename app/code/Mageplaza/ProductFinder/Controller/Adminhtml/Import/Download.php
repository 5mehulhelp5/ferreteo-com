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

namespace Mageplaza\ProductFinder\Controller\Adminhtml\Import;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\Result\Redirect as RedirectAlias;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\Directory\ReadFactory;

/**
 * Class Download
 * @package Mageplaza\ProductFinder\Controller\Adminhtml\Import
 */
class Download extends Action
{
    const MP_IMPORT_EXPORT_CMS_MODULE = 'Mageplaza_ProductFinder';

    /**
     * @var RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var ReadFactory
     */
    protected $readFactory;

    /**
     * @var ComponentRegistrar
     */
    protected $componentRegistrar;

    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * Download constructor.
     *
     * @param Context $context
     * @param FileFactory $fileFactory
     * @param RawFactory $resultRawFactory
     * @param ReadFactory $readFactory
     * @param ComponentRegistrar $componentRegistrar
     */
    public function __construct(
        Context $context,
        FileFactory $fileFactory,
        RawFactory $resultRawFactory,
        ReadFactory $readFactory,
        ComponentRegistrar $componentRegistrar
    ) {
        $this->fileFactory        = $fileFactory;
        $this->resultRawFactory   = $resultRawFactory;
        $this->readFactory        = $readFactory;
        $this->componentRegistrar = $componentRegistrar;
        parent::__construct($context);
    }

    /**
     * @return Redirect|ResponseInterface|Raw|RedirectAlias|ResultInterface
     * @throws FileSystemException
     */
    public function execute()
    {
        $fileName         = $this->getRequest()->getParam('filename');
        $moduleDir        = $this->componentRegistrar->getPath(
            ComponentRegistrar::MODULE,
            self::MP_IMPORT_EXPORT_CMS_MODULE
        );
        $fileAbsolutePath = $moduleDir . '/Files/Sample/' . $fileName;
        $directoryRead    = $this->readFactory->create($moduleDir);
        $filePath         = $directoryRead->getRelativePath($fileAbsolutePath);

        if (!$directoryRead->isFile($filePath)) {
            /** @var Redirect $resultRedirect */
            $this->messageManager->addErrorMessage(__('There is no sample file for this entity.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/import');

            return $resultRedirect;
        }

        $fileSize = isset($directoryRead->stat($filePath)['size'])
            ? $directoryRead->stat($filePath)['size'] : null;

        $this->fileFactory->create(
            $fileName,
            null,
            DirectoryList::VAR_DIR,
            'application/octet-stream',
            $fileSize
        );

        /** @var Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();
        $resultRaw->setContents($directoryRead->readFile($filePath));

        return $resultRaw;
    }
}
