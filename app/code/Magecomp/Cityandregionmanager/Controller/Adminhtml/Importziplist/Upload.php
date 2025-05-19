<?php
namespace Magecomp\Cityandregionmanager\Controller\Adminhtml\Importziplist;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Backend\Model\Session;

class Upload extends Action
{
    protected $_directoryList;
    protected $_jsonHelper;
    protected $_uploaderFactory;
    protected $session;

    public function __construct(
        Action\Context $context,
        DirectoryList $directoryList,
        Filesystem $filesystem,
        UploaderFactory $_uploaderFactory,
        Session $session,
        JsonHelper $jsonHelper
    )
    {
        $this->_jsonHelper = $jsonHelper;
        $this->_uploaderFactory = $_uploaderFactory;
        $this->_directoryList = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $this->session = $session;
        parent::__construct($context);
    }

    public function execute()
    {
        try{
            if(isset($this->getRequest()->getFiles("import_zip_list")['name'])) {
                $uploader = $this->_uploaderFactory->create(['fileId' => 'import_zip_list']);
                $workingDir = $this->_directoryList->getAbsolutePath('tmp/');
                $result = $uploader->save($workingDir);

                if(array_key_exists('file',$result) && array_key_exists('path',$result))
                {
                    $finalpath = $result['file'];
                    $this->session->setZipFileName($finalpath);
                }
                return $this->jsonResponse(['error' => "File uploaded successfully! Now click on import data."]);
            }
        }catch (\Exception $e){
            return $this->jsonResponse(['error' => $e->getMessage()]);
        }
    }

    public function jsonResponse($response = '')
    {
        return $this->getResponse()->representJson($this->_jsonHelper->jsonEncode($response));
    }

}
