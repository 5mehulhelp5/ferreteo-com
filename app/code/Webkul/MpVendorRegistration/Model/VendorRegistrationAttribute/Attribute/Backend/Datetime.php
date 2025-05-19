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

class Datetime extends \Magento\Eav\Model\Entity\Attribute\Backend\Datetime
{

    protected $request;

    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
    ) {
        $this->request = $request;
        parent::__construct($localeDate);
    }
    /**
     * Formatting date value before save
     *
     * Should set (bool, string) correct type for empty value from html form,
     * necessary for further process, else date string
     *
     * @param \Magento\Framework\DataObject $object
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return $this
     */
    public function beforeSave($object)
    {
        $attributeCode = $this->getAttribute()->getName();
        $wkvalue = $this->request->getPostValue();
        $explodedValue = explode('_', $attributeCode);

        if (is_array($explodedValue)) {
            if ('wkmpvr' == $explodedValue[0]) {
                $attributeName = $this->getAttribute()->getName();
                $wkDate = '';
                if (array_key_exists('customer', $wkvalue)) {
                    if (array_key_exists($attributeName, $wkvalue['customer'])) {
                        $wkDateValue = explode(' ', $wkvalue['customer'][$attributeName]);
                        if (is_array($wkDateValue) && count($wkDateValue) > 1) {
                            $explodedDate = explode('-', $wkDateValue[0]);
                            $reverseDate = array_reverse($explodedDate);
                            $wkDate = implode('-', $reverseDate);
                          // $wkDate = $explodedDate[1].'/'.$explodedDate[2].'/'.$explodedDate[0];
                        } else {
                            $wkDate = $wkDateValue[0];
                        }
                        $_formated = $wkDate . '_is_formated';

                        $this->checkAttribute($_formated, $attributeName, $object);
                    }
                }
            }
        } else {
            $attributeName = $this->getAttribute()->getName();
            $_formated = $object->getData($attributeName . '_is_formated');
            if (!$_formated && $object->hasData($attributeName)) {
                try {
                    $value = $this->formatDate($object->getData($attributeName));
                } catch (\Exception $e) {
                    throw new \Magento\Framework\Exception\LocalizedException(__('Invalid date'));
                }

                if ($value == null) {
                    $value = $object->getData($attributeName);
                }

                $object->setData($attributeName, $value);
                $object->setData($attributeName . '_is_formated', true);
            }
        }
          return $this;
    }
    /**
     *
     */
    public function checkAttribute($_formated, $attributeName, $object)
    {
        if (!$_formated && $object->hasData($attributeName)) {
            try {
                $value = $this->formatDate($wkDate);
            } catch (\Exception $e) {
                throw new \Magento\Framework\Exception\LocalizedException(__('Invalid date'));
            }
            if ($value == null) {
                $value = $object->getData($attributeName);
            }
            $object->setData($attributeName, $value);
            $object->setData($attributeName . '_is_formated', true);
        }
    }
}
