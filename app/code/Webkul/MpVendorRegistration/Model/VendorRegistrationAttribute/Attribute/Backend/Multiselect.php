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
namespace Webkul\MpVendorRegistration\Model\VendorRegistrationAttribute\Attribute\Backend;

class Multiselect extends \Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend
{
    /**
     * Core store config
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    protected $request;

    /**
     * Construct
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Request\Http $request
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->request = $request;
    }

    /**
     * Before Attribute Save Process
     *
     * @param \Magento\Framework\DataObject $object
     * @return $this
     */
    public function beforeSave($object)
    {
        $attributeCode = $this->getAttribute()->getName();
        $value = $this->request->getPostValue();
        $explodedValue = explode('_', $attributeCode);
        if (is_array($explodedValue)) {
            if ('wkmpvr' == $explodedValue[0]) {
                 //if ($this->getAttribute()->getIsUserDefined()) {
                    $data = $this->request->getPostValue();
                if (isset($data[$attributeCode]) && !is_array($data[$attributeCode])) {
                    $data = [];
                }
                if (isset($data[$attributeCode])) {
                    $object->setData($attributeCode, join(',', $data[$attributeCode]));
                }
              //  }
                if (!$object->hasData($attributeCode)) {
                    $object->setData($attributeCode, false);
                }
                return $this;
            }
        }

        return parent::beforeSave($object);
    }

    /**
     * After Load Attribute Process
     *
     * @param \Magento\Framework\DataObject $object
     * @return $this
     */
    public function afterLoad($object)
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        if ($this->getAttribute()->getIsUserDefined()) {
            $data = $object->getData($attributeCode);
            if ((!is_array($data) && $data) && (!$object->hasData($attributeCode)
               && $this->getAttribute()->getDefaultValue() !== '')) {
                $object->setData($attributeCode, explode(',', $data));
            } elseif (($data != null) &&
              (!$object->hasData($attributeCode) && $this->getAttribute()->getDefaultValue() !== '')) {
                $object->setData($attributeCode, []);
            }
        }
        return $this;
    }
}
