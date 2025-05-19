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
use Magento\Framework\File\Uploader;
use Mageplaza\Core\Helper\Media;

/**
 * Class Image
 * @package Mageplaza\ProductFinder\Helper
 */
class Image extends Media
{
    const TEMPLATE_IMAGE_PATH = 'productfinder';

    /**
     * @param array $data
     * @param array $result
     * @param string $inputName
     *
     * @return $this
     */
    public function _uploadImage(&$data, &$result, $inputName = 'image')
    {
        if (isset($data['image']['delete']) && $data['image']['delete']) {
            $data['image'] = '';
        } else {
            try {
                $uploader = $this->uploaderFactory->create(['fileId' => $inputName]);
                $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);
                $uploader->setAllowCreateFolders(true);

                $path          = $this->getBaseMediaPath(self::TEMPLATE_IMAGE_PATH);
                $image         = $uploader->save(
                    $this->mediaDirectory->getAbsolutePath($path)
                );
                $data['image'] = $image['file'];
            } catch (Exception $e) {
                $data['image'] = isset($data['image']) ? $data['image'] : '';
                if ($e->getCode() !== Uploader::TMP_NAME_EMPTY) {
                    $result['success'] = false;
                    $result['message'] = $e->getMessage();
                }
            }
        }

        return $this;
    }

    /**
     * @param $image
     *
     * @return string
     */
    public function getImageUrl($image)
    {
        $path = $this->getMediaPath($image, self::TEMPLATE_IMAGE_PATH);

        return $this->getMediaUrl($path);
    }
}
