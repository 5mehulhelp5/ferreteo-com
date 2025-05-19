<?php
namespace Magecomp\Cityandregionmanager\Controller\Ajax;

use Magento\Framework\App\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Magecomp\Cityandregionmanager\Model\ResourceModel\Zip\CollectionFactory as ZipCollection;
use Magecomp\Cityandregionmanager\Model\Config;
use Magento\Catalog\Model\Product;
use Magento\Framework\View\Result\Page;

class Getzip extends Action\Action
{
    public $_resultJsonFactory;

    protected $_zipCollection;

    protected $_config;

    protected $_product;

    protected $_page;

    public function __construct(
        Action\Context $context,
        ZipCollection $zipCollection,
        Config $config,
        Product $product,
        Page $page,
        JsonFactory $resultJsonFactory
    )
    {
        $this->_resultJsonFactory   = $resultJsonFactory;
        $this->_zipCollection       = $zipCollection;
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
            $post = $this->getRequest()->getParam('selected_city');

            $collection = $this->_zipCollection->create()
                ->addFieldToFilter('cities_name',$post)
                ->setOrder('zip_code','ASC')
                ->getData();
            return (!empty($collection)) ? $result->setData(['request' => 'OK', 'result' => $collection]) : $result->setData(['request' => 'No Postal code!', 'result' => 'No Postal code!']);
        }
        else
        {
            return $result->setData(['request' => 'AJAX ERROR']);
        }
    }
}