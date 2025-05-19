<?php
namespace Magebees\SubcategoryListing\Helper;
use Magento\Framework\App\Helper\AbstractHelper;
class Data extends AbstractHelper
{
    protected $_storeManager;
    protected $date;
    protected $_filesystem;
    protected $scopeConfig;
    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollection,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\RequestInterface $requests
    ) {
        $this->_storeManager = $storeManager;
        $this->_request = $request;
        $this->_registry = $registry;
        $this->_coreRegistry = $coreRegistry;
        $this->objectmanager = $objectmanager;
        $this->_categoryCollection = $categoryCollection;
        $this->scopeConfig = $scopeConfig;
        $this->requests = $requests;
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue('subcategorylisting/general/enable', $storeScope);
    }

    public function getProduct() {
        return $this->_registry->registry('current_product');
    }
    
    public function getCurrentCategory(){
        return $this->_registry->registry('current_category');
    }
    
    public function getCurrentUrl(){
        return $this->_urlBuilder->getCurrentUrl();
    }
    
    public function getCurrentPage(){
        return $this->_request->getFullActionName();
    }
    
    public function getBaseUrl(){
        return $this->_storeManager->getStore()->getBaseUrl();
    }
    
    public function getObjectManager() {
        return $this->objectmanager;
    }
    
    public function getMediaUrl()
    {
        $mediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $mediaUrl;
    }
    public function getIddata()
    {
        return  $this->requests->getParams(); 
    }

    public function getCatImageBaseUrl()
    {
        return $this->getMediaUrl().'catalog/category/';
		//return $this->getMediaUrl().'catalog/tmp/category/';
    }	public function getCatImageBaseUrlWidget()    {        return $this->getBaseUrl();    }
    public function getCatplaceHolderImg() {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue('subcategorylisting/general/subcatplaceholder', $storeScope);
     }
    public function getCatImgHeight() {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue('subcategorylisting/general/subcatimage_height', $storeScope);
     }
    public function getCatImgWidth() {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue('subcategorylisting/general/subcatimage_width', $storeScope);
     }
    public function getSubcatList() {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue('subcategorylisting/general/subcategories_layout', $storeScope);
     }
     public function getPageLayout() {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue('subcategorylisting/general/mbpagelayout', $storeScope);
     }
     public function getListLayout() {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue('subcategorylisting/general/subcategories_layout', $storeScope);
     }
     public function getIsparentcatimg() {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue('subcategorylisting/general/mbcategory_image', $storeScope);
     }
}