<?php
namespace Magecomp\Cityandregionmanager\Controller\Ajax;

use Magento\Framework\App\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Magecomp\Cityandregionmanager\Model\States;
use Magecomp\Cityandregionmanager\Model\Config;
use Magento\Catalog\Model\Product;
use Magento\Framework\View\Result\Page;

class Addnewstate extends Action\Action
{
    public $_resultJsonFactory;

    protected $_statesModel;

    protected $_config;

    protected $_product;

    protected $_page;

    public function __construct(
        Action\Context $context,
        States $statesModel,
        Config $config,
        Product $product,
        Page $page,
        JsonFactory $resultJsonFactory
    )
    {
        $this->_resultJsonFactory   = $resultJsonFactory;
        $this->_statesModel         = $statesModel;
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
            $post = $this->getRequest()->getParam('state_name');

            $state = $this->_statesModel;
            $state->setStatesName($post);
            $state->save();

            return  $result->setData(['request' => 'OK']);
        }
        else
        {
            return $result->setData(['request' => 'AJAX ERROR']);
        }
    }
}