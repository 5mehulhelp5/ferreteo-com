<?php
/**
 * Webkul Software
 *
 * @category  Webkul
 * @package   Webkul_MpVendorRegistration
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\MpVendorRegistration\Controller\Seller;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

class Region extends Action
{
    protected $regionColFactory;
    
    protected $resultJsonFactory;
    
    /**
     * Undocumented function
     *
     * @param Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Directory\Model\RegionFactory $regionColFactory
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Directory\Model\RegionFactory $regionColFactory
    ) {
        $this->regionColFactory = $regionColFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }
    /**
     * get collection on country id
     *
     * @return jsondata
     */
    public function execute()
    {
        $result = [];
        $result = $this->resultJsonFactory->create();
        $regions = $this->regionColFactory->create()->getCollection()
                  ->addFieldToFilter('country_id', $this->getRequest()->getParam('country'));
        return $result->setData(['success' => true,'value'=>$regions->getData()]);
    }
}
