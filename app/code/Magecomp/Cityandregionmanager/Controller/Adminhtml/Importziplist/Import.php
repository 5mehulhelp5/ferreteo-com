<?php
namespace Magecomp\Cityandregionmanager\Controller\Adminhtml\Importziplist;

use Magento\Backend\App\Action;
use Magento\Framework\File\Csv;
use Magecomp\Cityandregionmanager\Model\Zip;
use Magecomp\Cityandregionmanager\Model\ResourceModel\Zip\CollectionFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Backend\Model\Session;

class Import extends Action
{
    protected $_csv;
    protected $_zipListModel;
    protected $_zipListCollection;
    protected $_directoryList;
    protected $session;
    protected $filesystem;

    public function __construct(
        Action\Context $context,
        Csv $csv,
        Zip $zipListModel,
        DirectoryList $directoryList,
        CollectionFactory $zipListCollectionFactory,
        Session $session,
        \Magento\Framework\Filesystem $filesystem
    ) {
        $this->_csv = $csv;
        $this->_zipListModel = $zipListModel;
        $this->_zipListCollection = $zipListCollectionFactory;
        $this->_directoryList = $directoryList;
        $this->session = $session;
        $this->filesystem = $filesystem;
        parent::__construct($context);
    }

    public function execute()
    {
        ini_set('max_execution_time', '150');
        $directory = $this->filesystem->getDirectoryWrite(DirectoryList::TMP);
        $directory->create();

        $resultRedirect = $this->resultRedirectFactory->create();
        $zip_list = $this->_zipListModel;

        $zip_list_collection = $this->_zipListCollection->create();
        $data = $zip_list_collection->getData();
        $zip_list_city = [];

        foreach ($data as $item)
        {
            $zip_list_city[] = $item['zip_code'];
        }

        $tmpDir = $this->_directoryList->getPath('tmp');
        $file = $tmpDir.'/'.$this->session->getZipFileName();

        if(!$this->session->getZipFileName()){
             $this->messageManager->addError( __('Please upload CSV file.') );
             return $resultRedirect->setPath('magecomp_cityandregionmanager/zip/index');
        }

        if (!isset($file)) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Please upload valid CSV file.'));
        }

        $csv = $this->_csv;
        $csv->setDelimiter(',');
        $csvData = $csv->getData($file);
        $duplicate = 0;
        $imported = 0;
        foreach ($csvData as $row => $data) {
            if (count($data) == 3)
            {
                if ($data[0] == 'State name') continue;
                if($data[0] == "" || $data[1] == "" || $data[2] == "") continue;

                if (!in_array($data[2],$zip_list_city))
                {
                    $zip_list->setData([
                        'states_name' => utf8_encode($data[0]),
                        'cities_name' => utf8_encode($data[1]),
                        'zip_code'    => $data[2]
                    ])->save();
                    $imported++;
                }
                else{
                    $duplicate++;
                }
            }
            else{
                $this->messageManager->addError('The list of zipcodes should be in three column!');
                $this->session->unsZipFileName();
                return $resultRedirect->setPath('*/*/index');
            }
        }
        if($imported)
        {
            $this->messageManager->addSuccess($imported.' zipcodes imported successfully!');
            $this->session->unsZipFileName();
        }
        if($duplicate){
            $this->messageManager->addWarning($duplicate.' zipcodes not import because already exist.');
            $this->session->unsZipFileName();
        }
        return $resultRedirect->setPath('magecomp_cityandregionmanager/zip/index');
    }
}
