<?php
namespace Magecomp\Cityandregionmanager\Controller\Ajax;

use Magento\Framework\App\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Magecomp\Cityandregionmanager\Model\ResourceModel\States\CollectionFactory as StatesCollection;
use Magecomp\Cityandregionmanager\Model\Config;
use Magento\Catalog\Model\Product;
use Magento\Framework\View\Result\Page;

class Getstates extends Action\Action
{
    public $_resultJsonFactory;

    protected $_statesCollection;

    protected $_config;

    protected $_product;

    protected $_page;

    public function __construct(
        Action\Context $context,
        StatesCollection $statesCollection,
        Config $config,
        Product $product,
        Page $page,
        JsonFactory $resultJsonFactory
    )
    {
        $this->_resultJsonFactory   = $resultJsonFactory;
        $this->_statesCollection    = $statesCollection;
        $this->_config              = $config;
        $this->_product             = $product;
        $this->_page                = $page;
        return parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->_resultJsonFactory->create();
        if ($this->getRequest()->isAjax())
        {
            $collection = $this->_statesCollection->create()
                ->setOrder('states_name','ASC')->addFieldToFilter('country_id',$this->getRequest()->getParam('selected_country'))
                ->getData();
            $new=array();
            $i=0;
            foreach ($collection as $data){
                $new[]=$data;
                $new[$i]['customstate']=__($data['states_name']);
                $i++;
            }
            return (!empty($collection)) ? $result->setData(['request' => 'OK', 'result' => $new]) : $result->setData(['request' => 'No States!', 'result' => __('No States!')]);
        }
        else
        {
            return $result->setData(['request' => __('AJAX ERROR')]);
        }
    }
}