<?php
namespace Magecomp\Cityandregionmanager\Controller\Adminhtml\Importcitieslist;

use Magento\Backend\App\Action;
use Magento\Framework\File\Csv;
use Magecomp\Cityandregionmanager\Model\Cities;
use Magecomp\Cityandregionmanager\Model\ResourceModel\Cities\CollectionFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Backend\Model\Session;

class Import extends Action
{
    protected $_csv;
    protected $_citiesListModel;
    protected $_citiesListCollection;
    protected $_directoryList;
    protected $session;
    protected $filesystem;

    public function __construct(
        Action\Context $context,
        Csv $csv,
        Cities $citiesListModel,
        DirectoryList $directoryList,
        CollectionFactory $citiesListCollectionFactory,
        Session $session,
        \Magento\Framework\Filesystem $filesystem
    ) {
        $this->_csv = $csv;
        $this->_citiesListModel = $citiesListModel;
        $this->_citiesListCollection = $citiesListCollectionFactory;
        $this->_directoryList = $directoryList;
        $this->session = $session;
        $this->filesystem = $filesystem;
        parent::__construct($context);
    }

    public function execute()
    {
        ini_set('max_execution_time', '100');

        $directory = $this->filesystem->getDirectoryWrite(DirectoryList::TMP);
        $directory->create();

        $resultRedirect = $this->resultRedirectFactory->create();
        $cities_list = $this->_citiesListModel;

        $cities_list_collection = $this->_citiesListCollection->create();
        $data = $cities_list_collection->getData();
        $cities_list_city = [];

        foreach ($data as $item)
        {
            $cities_list_city[] = $item['cities_name'];
        }

        $tmpDir = $this->_directoryList->getPath('tmp');
        $file = $tmpDir.'/'.$this->session->getCitiesFileName();
        if(!$this->session->getCitiesFileName()){
            $this->messageManager->addError( __('Please upload CSV file.') );
            return $resultRedirect->setPath('magecomp_cityandregionmanager/cities/index');

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
            if (count($data) == 2)
            {
                if ($data[0] == 'State name') continue;
                if($data[0] == "" || $data[1] == "") continue;

                if (!in_array($data[1],$cities_list_city))
                {
                    $cities_list->setData([
                        'states_name' => utf8_encode($data[0]),
                        'cities_name' => utf8_encode($data[1])
                    ])->save();
                    $imported++;
                }
                else{
                    $duplicate++;
                }
            }
            else{
                $this->messageManager->addError('The list of cities should be in two column!');
                 $this->session->unsCitiesFileName();
                return $resultRedirect->setPath('*/*/index');
            }
        }
        if($imported)
        {
            $this->messageManager->addSuccess($imported.' cities imported successfully!');
            $this->session->unsCitiesFileName();
        }
        if($duplicate){
            $this->messageManager->addWarning($duplicate.' cities not import because already exist.');
            $this->session->unsCitiesFileName();
        }

        return $resultRedirect->setPath('magecomp_cityandregionmanager/cities/index');
    }
}
