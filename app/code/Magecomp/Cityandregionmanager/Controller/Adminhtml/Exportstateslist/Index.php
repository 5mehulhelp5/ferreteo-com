<?php
namespace Magecomp\Cityandregionmanager\Controller\Adminhtml\Exportstateslist;

use Magento\Framework\App\Filesystem\DirectoryList;

class Index extends \Magento\Backend\App\Action
{

    protected $_fileFactory;
    protected $directory;
    protected $collectionFactory;


    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magecomp\Cityandregionmanager\Model\ResourceModel\States\CollectionFactory $StatesFactory) 
    {
        $this->_fileFactory = $fileFactory;
        $this->directory = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $this->collectionFactory = $StatesFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        try 
        {
            
            $collection = $this->collectionFactory->create()->getData();
           
            $name = date('m_d_Y_H_i_s');
            $filepath = 'export/states'.$name.'.csv';
            $this->directory->create('export');

              /* Open file */
            $stream = $this->directory->openFile($filepath, 'w+');
            $stream->lock();
            
            $header = ['Country Name', 'State Name'];
            $stream->writeCsv($header);
            
            foreach ($collection as $state) {
                $data = [];
                $data[] = $state['country_id'];
                $data[] = $state['states_name'];
                $stream->writeCsv($data);
            }
            
            $content = [];
            $content['type'] = 'filename'; // must keep filename
            $content['value'] = $filepath;
            $content['rm'] = '1'; //remove csv from var folder
     
            $csvfilename = 'state.csv';
            return $this->_fileFactory->create($csvfilename, $content, DirectoryList::VAR_DIR);

        }catch (\Exception $e){
            return $e->getMessage();
        }
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magecomp_Cityandregionmanager::states');
    }
}