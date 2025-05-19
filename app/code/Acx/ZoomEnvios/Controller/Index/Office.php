<?php

namespace Acx\ZoomEnvios\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Acx\ZoomEnvios\Model\Checkout\DataProvider;

class Office extends Action {

    /**
     * @var Magento\Framework\Controller\Result\JsonFactory 
     */
    
    protected $resultJsonFactory;
    
    /**
     * @var Acx\ZoomEnvios\Model\Checkout\DataProvider 
     */
    protected $_dataProvider;
    
    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param DataProvider $datavider
     */
    
    public function __construct(
        Context  $context,
        JsonFactory $resultJsonFactory,
        DataProvider $dataProvider

    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_dataProvider = $dataProvider;
        parent::__construct($context);
    }
  
    public function execute() {
    
        /* @var Magento\Framework\Controller\Result\JsonFactory $resultJson */
        
        $resultJson = $this->resultJsonFactory->create();
        if($this->getRequest()->isAjax()) {

            $officesList = \Zend_Json::decode($this->_dataProvider->getOffices(
                    $this->getRequest()->getParam('city'))
                    );            
            return $resultJson->setData($officesList);
        }

   } 
}
