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

namespace Mageplaza\ProductFinder\Helper;

use Exception;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\File\Csv;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\ObjectManagerInterface;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\Core\Helper\Media;

/**
 * Class File
 * @package Mageplaza\ProductFinder\Helper
 */
class File extends Media
{
    const TEMPLATE_FILE_PATH = 'productfinder/import';

    /**
     * @var Csv
     */
    protected $csv;

    /**
     * @var DirectoryList
     */
    protected $dirList;

    /**
     * File constructor.
     *
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     * @param StoreManagerInterface $storeManager
     * @param Filesystem $filesystem
     * @param UploaderFactory $uploaderFactory
     * @param AdapterFactory $imageFactory
     * @param Csv $csv
     * @param DirectoryList $dirList
     *
     * @throws FileSystemException
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        Filesystem $filesystem,
        UploaderFactory $uploaderFactory,
        AdapterFactory $imageFactory,
        Csv $csv,
        DirectoryList $dirList
    ) {
        $this->csv     = $csv;
        $this->dirList = $dirList;
        parent::__construct($context, $objectManager, $storeManager, $filesystem, $uploaderFactory, $imageFactory);
    }

    /**
     * @param array $data
     * @param array $result
     * @param string $inputName
     *
     * @return $this
     */
    public function _uploadFile(&$data, &$result, $inputName = 'import_file')
    {
        try {
            $uploader = $this->uploaderFactory->create(['fileId' => $inputName]);
            $uploader->setAllowedExtensions(['csv']);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $uploader->setAllowCreateFolders(true);

            $path = $this->getBaseMediaPath(self::TEMPLATE_FILE_PATH);
            $file = $uploader->save(
                $this->mediaDirectory->getAbsolutePath($path)
            );

            $data['file'] = $this->dirList->getPath('media') . '/'
                . $this->getMediaPath($file['file'], self::TEMPLATE_FILE_PATH);
        } catch (Exception $e) {
            $data['file']      = isset($data['file']) ? $data['file'] : '';
            $result['success'] = false;
            $result['message'] = $e->getMessage();
        }

        return $this;
    }

    /**
     * @param string $file
     *
     * @return array|null
     * @throws Exception
     */
    public function getData($file)
    {
        $this->csv->setDelimiter('.');
        $this->csv->getData($file);

        return $this->csv->getData($file);
    }
}
