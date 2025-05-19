<?php
namespace Magecomp\Cityandregionmanager\Controller\Ajax;

use Magento\Framework\App\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Magecomp\Cityandregionmanager\Model\Cities;
use Magecomp\Cityandregionmanager\Model\Config;
use Magento\Catalog\Model\Product;
use Magento\Framework\View\Result\Page;

class Addnewcity extends Action\Action
{
    public $_resultJsonFactory;

    protected $_citiesModel;

    protected $_config;

    protected $_product;

    protected $_page;

    public function __construct(
        Action\Context $context,
        Cities $citiesModel,
        Config $config,
        Product $product,
        Page $page,
        JsonFactory $resultJsonFactory
    )
    {
        $this->_resultJsonFactory   = $resultJsonFactory;
        $this->_citiesModel         = $citiesModel;
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
            $city_post = $this->getRequest()->getParam('city_name');
            $state_post = $this->getRequest()->getParam('state_name');

            $city = $this->_citiesModel;
            $city->setCitiesName($city_post);
            $city->setStatesName($state_post);
            $city->save();

            return  $result->setData(['request' => 'OK']);
        }
        else
        {
            return $result->setData(['request' => 'AJAX ERROR']);
        }
    }
}