<?php
namespace Magecomp\Cityandregionmanager\Controller\Adminhtml\Importstateslist;

use Magento\Backend\App\Action;
use Magento\Framework\File\Csv;
use Magecomp\Cityandregionmanager\Model\States;
use Magecomp\Cityandregionmanager\Model\ResourceModel\States\CollectionFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Backend\Model\Session;

class Import extends Action
{
    protected $_csv;
    protected $_statesListModel;
    protected $_statesListCollection;
    protected $_directoryList;
    protected $session;
    protected $filesystem;

    public function __construct(
        Action\Context $context,
        Csv $csv,
        States $statesListModel,
        DirectoryList $directoryList,
        CollectionFactory $statesListCollectionFactory,
        Session $session,
        \Magento\Framework\Filesystem $filesystem
    ) {
        $this->_csv = $csv;
        $this->_statesListModel = $statesListModel;
        $this->_statesListCollection = $statesListCollectionFactory;
        $this->_directoryList = $directoryList;
        $this->session = $session;
        $this->filesystem = $filesystem;
        parent::__construct($context);
    }

    public function execute()
    {
        $directory = $this->filesystem->getDirectoryWrite(DirectoryList::TMP);
        $directory->create();

        $resultRedirect = $this->resultRedirectFactory->create();
        $states_list = $this->_statesListModel;

        $states_list_collection = $this->_statesListCollection->create();
        $data = $states_list_collection->getData();
        $states_list_state = [];

        foreach ($data as $item)
        {
            $states_list_state[] = $item['states_name'];
        }
        $tmpDir = $this->_directoryList->getPath('tmp');
        $file = $tmpDir.'/'.$this->session->getStatesFileName();

        if(!$this->session->getStatesFileName()){
            $this->messageManager->addError( __('Please upload CSV file.') );
            return $resultRedirect->setPath('magecomp_cityandregionmanager/states/index');
        }

        if (!isset($file)) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Please upload 
                valid CSV file.'));
        }

        $csv = $this->_csv;
        $csv->setDelimiter(',');
        $csvData = $csv->getData($file);
        $duplicate = 0;
        $imported = 0;
        foreach ($csvData as $row => $data) {
            if ($data[0] == 'Country Name') continue;
            if($data[0] == "" || $data[1] == "") continue;
            if (count($data) == 2)
            {
                if (!in_array($data[1],$states_list_state))
                {
                    $states_list->setData([
                        'country_id' => $data[0],'states_name' => utf8_encode($data[1])
                    ])->save();
                    $imported++;
                }else{
                    $duplicate++;
                }
            }
            else{
                $this->messageManager->addError('The list of states should be in one column!');
                $this->session->unsStatesFileName();
                return $resultRedirect->setPath('*/*/index');
            }
        }
        if($imported)
        {
            $this->messageManager->addSuccess($imported.' states imported successfully!');
            $this->session->unsStatesFileName();
        }
        if($duplicate){
            $this->messageManager->addWarning($duplicate.' states not import because already exist.');
            $this->session->unsStatesFileName();
        }

        return $resultRedirect->setPath('magecomp_cityandregionmanager/states/index');
    }
}
