<?php
/**
 * @category   Webkul
 * @package    Webkul_MpSellerBadge
 * @author     Webkul Software Private Limited
 * @copyright  Copyright (c)  Webkul Software Private Limited (https://webkul.com)
 * @license    https://store.webkul.com/license.html
 */
namespace Webkul\MpSellerBadge\Controller\Adminhtml\Badges;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Backend\App\Action;
use Magento\Framework\Filesystem;
use Webkul\MpSellerBadge\Api\BadgeRepositoryInterface;
use Webkul\MpSellerBadge\Model\Badge;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class Save extends \Magento\Backend\App\Action
{
    /**
     * badge repository
     * @var badgeRepository
     */
    protected $badgeRepository;

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $fileUploaderFactory;

    /**
     * object of Filesystem
     * @var [type]
     */
    protected $filesystem;

    /**
     * object of badge model
     * @var Badge
     */
    protected $badge;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    protected $filesystemFile;

    /**
     * @param Filesystem                                       $filesystem
     * @param Action\Context                                   $context
     * @param Badge                                            $badge
     * @param BadgeRepositoryInterface                         $badgeRepository
     * @param \Magento\Framework\Stdlib\DateTime\DateTime      $date
     * @param \Magento\Framework\Filesystem\Directory\Read     $readFactory
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     * @param \Magento\Framework\Filesystem\Io\File            $filesystemFile
     */
    public function __construct(
        Filesystem $filesystem,
        Action\Context $context,
        Badge $badge,
        BadgeRepositoryInterface $badgeRepository,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        TimezoneInterface $localeDate,
        \Magento\Framework\Filesystem\Io\File $filesystemFile
    ) {
        $this->badge = $badge;
        $this->date = $date;
        $this->badgeRepository = $badgeRepository;
        $this->filesystem = $filesystem;
        $this->fileUploaderFactory = $fileUploaderFactory;
        $this->localeDate = $localeDate;
        $this->filesystemFile = $filesystemFile;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_MpSellerBadge::m_badge');
    }

    /**
     * Save action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $errorMsg = null;
        $time = $this->localeDate->date()->format('Y-m-d H:i:s');
        $resultRedirect = $this->resultRedirectFactory->create();
        $imageData = [];
        $isImage = 0;
        $data = $this->getRequest()->getParams();
        $id = (int) $this->getRequest()->getParam('id');
        $isRankExist = $this->badgeRepository->checkBadgeRankExist($data['rank']);
        try {
            $imageUploadPath = $this->filesystem->getDirectoryRead(
                DirectoryList::MEDIA
            )->getAbsolutePath('sellerbadge/');
            if (!$this->filesystemFile->fileExists($imageUploadPath)) {
                 $this->filesystemFile->mkdir($imageUploadPath, 0755, true);
            }
            $uploader = $this->fileUploaderFactory->create(['fileId' => 'image']);
            $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(false);
            $imageData = $uploader->validateFile();
            $info = getimagesize($imageData['tmp_name']);
            if ($info) {
                $resultLogo = $uploader->save($imageUploadPath);
                if ($resultLogo['file']) {
                    $imageData['image'] = 'sellerbadge/'.$resultLogo['file'];
                }
            } else {
                $errorMsg = __('Image file is not correct.');
            }
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
        }

        if (empty($id) && !empty($errorMsg)) {
            $this->messageManager->addError($errorMsg);
            return $resultRedirect->setPath('*/*/');
        }
        if ($data) {
            if (!empty($imageData['image'])) {
                $data['badge_image_url'] = $imageData['image'];
            } else {
                unset($data['badge_image_url']);
            }

            $model = $this->badge;
            $data['updated_time'] = $time;
            if ($id) {
                $this->saveBadge($isRankExist, $data, $id, $model);
            } else {
                if (!$isRankExist) {
                    $data['created_at'] = $time;
                    $model->setData($data)->save();
                    $this->messageManager->addSuccess(__('Badge saved successfully.'));
                } else {
                    $this->messageManager->addError(__('Rank already exist.'));
                }
            }
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Save Badge When Id Exists
     *
     * @param int $isRankExist
     * @param array $data
     * @param int $id
     * @param collection $model
     * @return void
     */
    public function saveBadge($isRankExist, $data, $id, $model)
    {
        if (!$isRankExist || $isRankExist == $id) {
            $model->addData($data)->setId($id)->save();
            $this->messageManager->addSuccess(__('Badge updated successfully.'));
        } else {
            unset($data['rank']);
            $model->addData($data)->setId($id)->save();
            $this->messageManager->addError(__('Rank already exist.'));
        }
    }
}
