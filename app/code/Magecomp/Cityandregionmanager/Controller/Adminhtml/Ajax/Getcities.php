<?php
namespace Magecomp\Cityandregionmanager\Controller\Adminhtml\Ajax;

use Magento\Framework\App\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Magecomp\Cityandregionmanager\Model\ResourceModel\Cities\CollectionFactory as CitiesCollection;
use Magecomp\Cityandregionmanager\Model\Config;
use Magento\Catalog\Model\Product;
use Magento\Framework\View\Result\Page;

class Getcities extends Action\Action
{
    public $_resultJsonFactory;

    protected $_citiesCollection;

    protected $_config;

    protected $_product;

    protected $_page;

    public function __construct(
        Action\Context $context,
        CitiesCollection $citiesCollection,
        Config $config,
        Product $product,
        Page $page,
        JsonFactory $resultJsonFactory
    )
    {
        $this->_resultJsonFactory   = $resultJsonFactory;
        $this->_citiesCollection    = $citiesCollection;
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
            $post = $this->getRequest()->getParam('selected_state');

            $collection = $this->_citiesCollection->create()
                ->addFieldToFilter('states_name',$post)
                ->setOrder('cities_name','ASC')
                ->getData();
            return (!empty($collection)) ? $result->setData(['request' => 'OK', 'result' => $collection]) : $result->setData(['request' => 'No Cities!', 'result' => 'No Cities!']);
        }
        else
        {
            return $result->setData(['request' => 'AJAX ERROR']);
        }
    }
}