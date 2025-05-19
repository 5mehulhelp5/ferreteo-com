<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_MpVendorRegistration
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\MpVendorRegistration\Block\Adminhtml\Customer\Edit;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Button extends \Magento\Config\Block\System\Config\Form\Field
{

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    protected $objectManager;

    protected $vendorAttribute;

    const JS_TEMPLATE = 'customfields/customer/js.phtml';

     /**
      * @param \Magento\Backend\Block\Widget\Context $context
      * @param \Magento\Framework\Registry $registry
      * @param array $data
      */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Webkul\MpVendorRegistration\Model\VendorRegistrationAttributeFactory $vendorAttribute,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->objectManager = $objectManager;
        $this->vendorAttribute = $vendorAttribute;
        parent::__construct($context, $data);
    }
     
    /**
     * Set template to itself
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate(static::JS_TEMPLATE);
        }
        return $this;
    }
    /**
     * get vendor attribute
     *
     * @return vendorattribute
     */
    public function getVendorAttributes()
    {
        $vendorAttributes = $this->vendorAttribute->create()
                           ->getCollection()->addFieldToFilter('attribute_by_admin', 0);
        return $vendorAttributes;
    }
}
